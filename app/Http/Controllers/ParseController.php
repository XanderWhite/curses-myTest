<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;

class ParseController extends Controller
{

        public function parseHtml()
    {
        // Базовый URL
        $baseUrl = 'https://obrazoval.ru';

        // URL для парсинга
        $url = $baseUrl;

        // Создаем HTTP-клиент
        $client = new Client();
        $response = $client->get($url);

        // Получаем HTML-код страницы
        $html = $response->getBody()->getContents();

        // Создаем объект Crawler для парсинга
        $crawler = new Crawler($html);

        // Извлекаем основные категории
        $mainCategories = $crawler->filter('.directions__list-item')->each(function (Crawler $node) {
            if ($node->filter('.directions__title')->count() > 0 && $node->filter('.directions__link')->count() > 0) {
                return [
                    'name' => $node->filter('.directions__title')->text(),
                    'link' => $node->filter('.directions__link')->attr('href')
                ];
            }
            return null; // Возвращаем null, если элемент не найден
        });

        // Удаляем null значения
        $mainCategories = array_filter($mainCategories);

        // Извлекаем подкатегории и группируем ссылки
        $result = [];

        foreach ($mainCategories as $mainCategory) {
             try {
                // $mainCategory = $mainCategories[0];


                $subCategories = $crawler->filter('.direction-info__link')->each(function (Crawler $node) use ($baseUrl, $mainCategory) {

                    if ($node->count() > 0) {
                        $link = $node->attr('href');
                        // Проверяем, что ссылка подкатегории начинается с URL текущей категории
                        if (strpos($link, $mainCategory['link']) === 0) {
                            return [
                                'name' => $node->text(),
                                'link' => $baseUrl . $link // Добавляем базовый URL к ссылке
                            ];
                        }
                    }
                    return null; // Возвращаем null, если элемент не найден или не относится к категории
                });


                // Удаляем null значения и переиндексируем массив
                $subCategories = array_values(array_filter($subCategories));

                //--------------------------------------
                // foreach( $subCategories as  $subCategory){
                    // foreach ($subCategories as &$subCategory) {
                    //     return
                    //     // Получаем HTML-контент для каждой подкатегории
                    //     $subCategoryResponse = $client->get($subCategory['link']);
                    //     $subCategoryHtml = $subCategoryResponse->getBody()->getContents();
                    //     $subCategoryCrawler = new Crawler($subCategoryHtml);

                    //     // Получаем курсы для текущей подкатегории
                    //     // $courses = $this->getCourses($subCategoryCrawler, $baseUrl);

                    //     // Удаляем null значения и переиндексируем массив
                    //     // $courses = array_values(array_filter($courses));

                    //     // Добавляем курсы в текущую подкатегорию
                    //     $subCategory['courses'] = null;
                    // }




                // Добавляем категорию с подкатегориями в результат
                $result[] = [
                    'category' => $mainCategory['name'],
                    'subcategories' => $subCategories
                ];
            } catch (\Exception $e) {
                // Логируем ошибку, если страница недоступна
                $result[] = [
                    'category' => $mainCategory['name'],
                    'error' => 'Ошибка при загрузке страницы: ' . $e->getMessage()
                ];
            }
        }

        // Возвращаем результат в виде JSON
        return response()->json($result);
    }

    function getCourses($crawler, $baseUrl)
    {

        $courses = $crawler->filter('.l-course.b-bordered.b-courses__course');
        $node = $courses->eq(0);
        // ->each(function (Crawler $node) use ($baseUrl) {


            $title = $node->filter('.b-title__course')->text();

            $text = $node->filter('.l-course__description')->text();

            $price = $node->filter('.b-title__custom.l-course__price span')->text();
$price = str_replace([' ', '&nbsp;', '₽'], '', htmlentities($price)); // Удаляем пробелы, &nbsp; и ₽
$price = (int) $price; // Преобразуем в число
            $link = $node->filter('.l-course__description')->attr('href');

            $more = $node->filter('a.b-btn.b-btn--outline.l-course__btn')->each(function (Crawler $node) {
                if (stripos($node->text(), 'Подробнее') !== false) {
                    return $node->attr('href'); // Возвращаем только href
                }
                return null; // Возвращаем null, если текст не соответствует
            });

            $more = $baseUrl . array_values(array_filter($more))[0];

$more = $this-> getMore($more);

            return [
                'title' => $title,
                'text' => $text,
                'price' => $price,
                'link' => $link,
                'more' =>   $more
            ];
    }




    function getMore($link)
    {
        $client = new Client();

        $courseResponse = $client->get($link);
        $courseHtml = $courseResponse->getBody()->getContents();
        $crawler = new Crawler($courseHtml);


        $info = $crawler->filter('.l-course__owner-wrapper a')->each(function (Crawler $node) {

return $node->attr('href'); // Возвращаем только href

});

            $info = array_values(array_filter($info))[0];



            return [
                'moreLink' => $link,
                'info' => $info
            ];


    }
}

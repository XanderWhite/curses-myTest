<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;

use App\Models\Category;
use App\Models\Course;
use App\Models\Subcategory;
use App\Models\School;

class ParseController extends Controller
{
    // Базовый URL
    protected const BASE_URL = 'https://obrazoval.ru';

    /**
     * Получает объект Crawler для указанного URL.
     *
     * @param string $url
     * @return Crawler
     */
    protected function getCrawler(string $url): Crawler
    {
        try {
            $client = new Client();
            $response = $client->get($url);

            $html = $response->getBody()->getContents();
            return new Crawler($html);
        } catch (\Exception $e) {
            // Логируем ошибку или выбрасываем исключение
            throw new \RuntimeException("Не удалось получить данные с URL: {$url}. Ошибка: " . $e->getMessage());
        }
    }

    public function parseHtmlCategoriesSubcategories()
    {

        $crawler = $this->getCrawler(self::BASE_URL);

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

                $subCategories = $crawler->filter('.direction-info__link')->each(function (Crawler $node) use ($mainCategory) {

                    if ($node->count() > 0) {
                        $link = $node->attr('href');
                        // Проверяем, что ссылка подкатегории начинается с URL текущей категории
                        if (strpos($link, $mainCategory['link']) === 0) {
                            return [
                                'name' => $node->text(),
                                'link' => self::BASE_URL . $link
                            ];
                        }
                    }
                    return null; // Возвращаем null, если элемент не найден или не относится к категории
                });


                // Удаляем null значения и переиндексируем массив
                $subCategories = array_values(array_filter($subCategories));

                // Добавляем категорию с подкатегориями в результат
                $result[] = [
                    'category' => $mainCategory['name'],
                    'subcategories' => $subCategories
                ];

                //коментарий от случайного дублирования данных в БД
                // Сохраняем категорию и подкатегории в базе данных
                // $category = Category::firstOrCreate(['name' => $mainCategory['name']]);
                // foreach ($subCategories as $subCategory) {
                //     Subcategory::firstOrCreate([
                //         'category_id' => $category->id,
                //         'name' => $subCategory['name'],
                //         'link' => $subCategory['link']
                //     ]);
                // }



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

     /**
     * Добавление первой школы для заглушки в курсах
     */
    public function insertFirstSchool(){
$school = new School();
$school->name = 'Яндекс Практикум';
$school->description = '<p>Яндекс Практикум — сервис онлайн-обучения, где каждый может освоить цифровую профессию с нуля или получить новые навыки для дальнейшего профессионального развития.</p>';
$school->link = 'https://practicum.yandex.ru/';
$school->save();
echo 'school is saved';
    }

    public function parseHtmlSubcategoriesCourses()
    {
        $result = [];


        $categoryId = 0; // ID категории, для которой нужно получить подкатегории
        //добавленные id: 1 2 3 4 5 6 7 8 9 10


        $category = Category::find($categoryId);

        if ($category) {
            $subcategories = $category->subcategories;
            foreach ($subcategories as $subcategory) {

                // foreach ($subcategories as $subcategory) {
                $link = $subcategory->link;

                // Получаем курсы для текущей подкатегории
                $subCategoryCrawler = $this->getCrawler($link);

                if ($subCategoryCrawler) {
                    $courses = $this->getCourses($subCategoryCrawler);

                    // Удаляем null значения и переиндексируем массив
                    $courses = array_values(array_filter($courses));

                    // foreach ($courses as $course) {
                    //     // Добавляем данные в таблицу курсы
                    //     Course::create([
                    //         'subcategory_id' => $subcategory->id,
                    //         'school_id' => 1, //использую значение 1 в качестве заглушки, предварительно создав школу в insertFirstSchool();
                    //         'name' => $course['title'],
                    //         'description' => $course['text'],
                    //         'price' => $course['price'],
                    //         'link' => $course['link'],
                    //         'link-more' => $course['more'],
                    //    ]);
                    // }

                    $result[] = [
                        'subcategory' => $subcategory->name,
                        'link' => $subcategory->link,
                        'courses' => $courses
                    ];
                } else {
                    // Если не удалось получить курсы, добавляем сообщение об ошибке
                    $result[] = [
                        'subcategory' => $subcategory->name,
                        'link' => $subcategory->link,
                        'error' => 'Не удалось получить курсы'
                    ];
                }
            }
        } else {
            echo "Category not found.";
        }

        // Возвращаем результат в виде JSON
        return response()->json($result);
    }


/**
 * Получает данные о курсах из указанного URL.
 *
 * @param Crawler $crawler
 * @return Array $node
 */
    function getCourses($crawler)
    {

        $courses = $crawler->filter('.l-course.b-bordered.b-courses__course');
        // $node = $courses->eq(0);
        $node = $courses->each(function (Crawler $node)  {

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

        $more = self::BASE_URL . array_values(array_filter($more))[0];

        // $more = $this->getMore($more);

        return [
            'title' => $title,
            'text' => $text,
            'price' => $price,
            'link' => $link,
            'more' =>   $more
        ];
    });

    return $node;
    }

    public function parseHtmlSchool(){
        $category = Category::find(1);
        $subcategories = Subcategory::where('category_id', $category->id)->get();
        $courses = Course::whereIn('subcategory_id', $subcategories->pluck('id'))->get();

//         $category = Category::find(1);
// $courses = $category->subcategories()->with('courses')->get()->pluck('courses')->flatten();


        $result=[];

        foreach ($courses as $course) {
            $moreLink = $course['link-more'];

            $crawler = $this-> getCrawler($moreLink);

            $schoolLink = $crawler->filter('.l-course__owner-wrapper a')->first()->attr('href');

            $result[]= [
                'course_id' => $course['id'],
              'link-more'=>  $course['link-more'],
                'schoolLink' => self::BASE_URL.$schoolLink];
        }

         // Возвращаем результат в виде JSON
         return response()->json($result);
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

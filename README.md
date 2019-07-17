Создать небольшой проект, используя:
Symfony (любую версию)
MySql
Doctrine
Twig


Проект должен состоять из двух страниц:

1. Главная: site.com
В первом блоке вывести список категории в виде ссылок (Имя категории).
Во втором блоке вывести список первого товара каждой категории ( Имя_товара (Имя_категории) ).

2. Страница категории: site.com/category_name
Вывести все товары (только названия), принадлежащие выбранной категории.

**SQL**
************************* 
    SELECT `product_id`
    FROM
      `category` AS `t0` 
      INNER JOIN `category_product` 
        ON `t0`.`id` = `category_product`.`category_id`
    WHERE `category_product`.`category_id` = 3;

**Doctrine**
*************************
        $repo = $this->getDoctrine()->getRepository(Product::class);
        $q = $repo->createQueryBuilder("c")
            ->select('c.name')
            ->innerJoin('c.category', 'p')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id);
        $qb =$q->getQuery()->getResult();
*************************
Все делается без дизайна (простые списки).
Категории хранятся в таблице с 2 полями: id(primary key), name(unique key)
Товары хранятся в таблице с полями: id(primary key), name(unique key)
Между таблицами товаров и категорий действует отношение Many-to-Many
Структура таблиц должна описываться в phpDoc формате
Запросы должны выполняться с помощью ORM Doctrine
Можно использовать другие необходимые бандлы

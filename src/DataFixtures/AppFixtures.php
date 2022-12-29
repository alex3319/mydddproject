<?php
namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\ProductRelation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $optionType = [
        'to_add' => 1,
        'to_remove' => 2,
        'options' => 3
    ];

    public function load(ObjectManager $manager)
    {
        // перечень создаваемых категорий
        $categories = [
            'shaurma' => [
                'name' => 'Шаурма',
                'sort' => 1
            ],
            'pizza' => [
                'name' => 'Пицца',
                'sort' => 2
            ],
            'sushi' => [
                'name' => 'Суши',
                'sort' => 3
            ],
            'drinks' => [
                'name' => 'Напитки',
                'sort' => 4
            ],
            'sauce' => [
                'name' => 'Соусы',
                'sort' => 5
            ],
            'options' => [
                'name' => 'Опции',
                'is_active' => 0
            ]
        ];

        // генерация категорий
        foreach ($categories as $alias => $c) {
            $category = new Category();
            $category->setName($c['name']);
            $category->setIsActive($c['is_active'] ?? 1);
            $category->setSort($c['sort'] ?? 0);
            $manager->persist($category);
            $manager->flush();

            $categories[$alias]['entityId'] = $category->getId();
        }

        // перечень опций
        $optionProducts = [
            'mozarella' => [
                'name' => 'Сыр Моцарелла',
                'price' => 35
            ],
            'halapenio' => [
                'name' => 'Халапеньо',
                'price' => 15
            ],
            'bekon' => [
                'name' => 'Бекон'
            ],
            'cucubmer' => [
                'name' => 'Маринованный огурец',
                'price' => 25,
            ],
            'cut-12' => [
                'name' => 'Резать на 12 кусочков',
            ],
            'cut-16' => [
                'name' => 'Резать на 16 кусочков',
            ]
        ];

        // генерация опций
        foreach ($optionProducts as $alias => $p) {
            $product = new Product();
            $product->addCategory($manager->getRepository(Category::class)->find($categories['options']['entityId']));
            $product->setName($p['name']);
            $product->setPrice($p['price'] ?? 0);
            $manager->persist($product);
            $manager->flush();

            $optionProducts[$alias]['entityId'] = $product->getId();
        }

        // перечень товаров разбитых по категориям
        $catalog = [
            'shaurma' => [
                [
                    'name' => 'Грильяс Нежный',
                    'description' => 'Пшеничная лепешка, филе цыпленка, капуста, картофель фри, лук фри, бекон, болгарский перец, соус “Сладкий черри”',
                    'price' => 180,
                    'weight' => 300,
                    'is_new' => true,
                    'is_hit' => false,
                    'to_add' => ['mozarella', 'halapenio', 'cucubmer'],
                    'to_remove' => ['bekon'],
                    'options' =>  ['cut-12', 'cut-16']
                ], [
                    'name' => 'Грильяс Жгучий',
                    'description' => 'Пшеничная лепешка, рубленое мясо цыпленка в томатном соусе, шампиньоны, лук фри, картофель фри, халапеньо.',
                    'price' => 180,
                    'weight' => 300,
                    'is_new' => false,
                    'is_hit' => false
                ],  [
                    'name' => 'Царская',
                    'description' => 'Лаваш, филе цыпленка, соус на выбор, морковь по-корейски, капуста, томаты, огурец, картофель фри.',
                    'price' => 200,
                    'weight' => 300,
                    'is_new' => false,
                    'is_hit' => true
                ], [
                    'name' => 'Мясная',
                    'description' => 'Лаваш, соус на выбор, говядина в хрустящей панировке, томаты, огурец, картофель фри, сыр голландский, соус сладкий черри.',
                    'price' => 200,
                    'weight' => 300,
                    'is_new' => false,
                    'is_hit' => false
                ], [
                    'name' => 'Мужская',
                    'description' => 'Лаваш, соус на выбор, филе цыпленка, омлет яичный, маринованный огурец, картофель фри, лук фри.',
                    'price' => 180,
                    'weight' => 300,
                    'is_new' => false,
                    'is_hit' => false
                ]
            ],
            'pizza' => [
                [
                    'name' => 'Баварская',
                    'description' => 'Тесто, сыр Моцарелла, рубленое мясо цыпленка, красный соус, бекон, фирменный соус, маринованный огурец, сырный соус, орегано.',
                    'price' => 529,
                    'weight' => 600,
                    'is_new' => false,
                    'is_hit' => true
                ], [
                    'name' => 'Летняя',
                    'description' => 'Тесто, сыр Моцарелла, сливочный соус, рубленое мясо цыпленка, лук маринованный, перец болгарский, зеленый лук, орегано.',
                    'price' => 529,
                    'weight' => 580,
                    'is_new' => true,
                    'is_hit' => false
                ], [
                    'name' => 'Гавайская',
                    'description' => 'есто, сыр Моцарелла, ветчина, бортик на выбор, ананас, красный соус, филе цыпленка, белый соус, орегано. Диаметр - 40 см.',
                    'price' => 829,
                    'weight' => 1170,
                    'is_new' => false,
                    'is_hit' => false
                ], [
                    'name' => 'Цезарь',
                    'description' => 'Тесто, сыр Моцарелла, филе цыпленка, сливочный соус, томаты черри, салат айсберг, фирменный соус цезарь, сыр твердый "Гоюс", орегано. Диаметр - 30 см.',
                    'price' => 700,
                    'weight' => 605,
                    'is_new' => false,
                    'is_hit' => false
                ], [
                    'name' => 'Пепперони',
                    'description' => 'Тесто, сыр Моцарелла, бортик на выбор, красный соус, пепперони, орегано. Диаметр - 40 см.',
                    'price' => 850,
                    'weight' => 1080,
                    'is_new' => false,
                    'is_hit' => false
                ]
            ],
            'sushi' => [
                [
                    'name' => 'Остров Краби',
                    'description' => 'Рис, снежный краб, соус спайси, икра тобико, форель, огурец свежий, кунжут, паприка.',
                    'price' => 275,
                    'weight' => 200,
                    'is_new' => false,
                    'is_hit' => false
                ], [
                    'name' => 'Филадельфия стандарт',
                    'description' => 'Рис, форель, сыр Креметте, водоросли нори.',
                    'price' => 510,
                    'weight' => 220,
                    'is_new' => true,
                    'is_hit' => false
                ], [
                    'name' => 'Темпура Люкс',
                    'description' => 'Рис, кляр, сыр Креметте, королевские креветки, сухари панко, икра тобико, водоросли нори.',
                    'price' => 420,
                    'weight' => 285,
                    'is_new' => false,
                    'is_hit' => true
                ], [
                    'name' => 'Крит',
                    'description' => 'Рис, кляр,форель, огурец свежий, перец болгарский, стружка тунца, зеленый лук, соус спайси.',
                    'price' => 325,
                    'weight' => 245,
                    'is_new' => false,
                    'is_hit' => false
                ], [
                    'name' => 'Жара Аляски',
                    'description' => 'Рис, снежный краб, лук криспи, огурец свежий, яичный блин, творожный сыр Креметте, соус унаги.',
                    'price' => 250,
                    'weight' => 230,
                    'is_new' => false,
                    'is_hit' => false
                ]
            ],
        ];

        // генерация товаров со всеми связями
        foreach ($catalog as $categoryAlias => $productsLIst) {
            foreach ($productsLIst as $p) {
                $product = new Product();
                $product->setName($p['name']);
                $product->setDescription($p['description']);
                $product->setPrice($p['price']);
                $product->setWeight($p['weight']);
                $product->setIsNew($p['is_new']);
                $product->setIsHit($p['is_hit']);

                $product->addCategory($manager->getRepository(Category::class)->find($categories[$categoryAlias]['entityId']));

                $manager->persist($product);
                $manager->flush();

                $productId = $product->getId();

                // позиции, которые можно добавить
                foreach ($this->optionType as $alias => $type) {
                    if (isset($p[$alias])) {
                        foreach ($p[$alias] as $optionAlias) {
                            $productRelation = new ProductRelation();
                            $productRelation->setSource($productId);
                            $productRelation->setTarget($optionProducts[$optionAlias]['entityId']);
                            $productRelation->setType($type);

                            $manager->persist($productRelation);
                            $manager->flush();
                        }
                    }
                }
            }
        }
    }
}
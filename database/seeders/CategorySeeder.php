<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Adhesives & Sealants',
                'description' => 'Adhesive products and sealing solutions for various industrial applications',
                'subcategories' => [
                    'Adhesives',
                    'Adhesive Tapes',
                    'Tapes',
                    'Pressure Sensitive Tapes',
                    'Adhesive Dispensing Equipment'
                ]
            ],
            [
                'name' => 'Automation & Electronics',
                'description' => 'Automation equipment and electronic components for industrial applications',
                'subcategories' => [
                    'Automation Equipment',
                    'Printed Circuit Boards (PCB)',
                    'Electronic Enclosures',
                    'Cleanrooms',
                    'EMI/RFI Shielding'
                ]
            ],
            [
                'name' => 'Chemicals',
                'description' => 'Chemical products and coatings for industrial use',
                'subcategories' => [
                    'Coating',
                    'Desiccants',
                    'Corrosion Resistant Coatings',
                    'Optical Coatings',
                    'PTFE Coatings'
                ]
            ],
            [
                'name' => 'Custom Manufacturing & Fabricating',
                'description' => 'Custom manufacturing and fabrication services',
                'subcategories' => [
                    'Metal Fabrication',
                    'CNC Machining',
                    'Metal Stampings',
                    'Screw Machine Products',
                    'Tube Fabricating'
                ]
            ],
            [
                'name' => 'Electrical & Power Generation',
                'description' => 'Electrical components and power generation equipment',
                'subcategories' => [
                    'Batteries',
                    'Transformers',
                    'Magnets',
                    'Custom Transformers',
                    'Neodymium Magnets'
                ]
            ],
            [
                'name' => 'Engineering & Consulting',
                'description' => 'Engineering services and consulting solutions',
                'subcategories' => [
                    'Engineering Services',
                    'Prototypes',
                    'Rapid Prototyping Services',
                    'Product Development',
                    'Exporters, Importers'
                ]
            ],
            [
                'name' => 'Hardware',
                'description' => 'Hardware components and fasteners',
                'subcategories' => [
                    'Fasteners',
                    'Gaskets',
                    'Bolts',
                    'O Rings',
                    'Hinges'
                ]
            ],
            [
                'name' => 'Instruments & Controls',
                'description' => 'Measurement instruments and control systems',
                'subcategories' => [
                    'Laboratory Equipment & Supplies',
                    'Flow Meters',
                    'Sensors',
                    'Calibration Services',
                    'Leak Detectors'
                ]
            ],
            [
                'name' => 'Machinery, Tools & Supplies',
                'description' => 'Industrial machinery, tools and supplies',
                'subcategories' => [
                    'Special & Custom Machinery',
                    'Bearings',
                    'Gears',
                    'Brushes',
                    'Springs'
                ]
            ],
            [
                'name' => 'Materials Handling',
                'description' => 'Materials handling equipment and solutions',
                'subcategories' => [
                    'Special & Custom Machinery',
                    'Bearings',
                    'Gears',
                    'Brushes',
                    'Springs'
                ]
            ],
            [
                'name' => 'Metals & Metal Products',
                'description' => 'Metal products and metalworking services',
                'subcategories' => [
                    'Aluminum',
                    'Steel Service Centers',
                    'Stainless Steel',
                    'Wire Forms',
                    'Powdered Metal Parts'
                ]
            ],
            [
                'name' => 'Plants & Facility Equipment',
                'description' => 'Plant and facility equipment for industrial operations',
                'subcategories' => [
                    'Electric Heaters',
                    'Nameplates',
                    'Industrial Vacuum Cleaners',
                    'Dust Collecting Systems',
                    'Noise Control'
                ]
            ],
            [
                'name' => 'Plastics & Rubber',
                'description' => 'Plastic and rubber products and manufacturing',
                'subcategories' => [
                    'Injection Molded Plastics',
                    'Molded Plastics',
                    'Molded Rubber Goods',
                    'Extruded Plastics',
                    'Custom Injection Molded Plastics'
                ]
            ],
            [
                'name' => 'Process Equipment',
                'description' => 'Industrial process equipment and systems',
                'subcategories' => [
                    'Heat Exchangers',
                    'Pressure Vessels',
                    'Misers',
                    'Ovens',
                    'Heating Elements'
                ]
            ],
            [
                'name' => 'Pumps, Valves & Accessories',
                'description' => 'Pumping systems, valves and related accessories',
                'subcategories' => [
                    'Ball Valves',
                    'Pumps',
                    'Plastic Tubing',
                    'Stainless Steel Tubing',
                    'Vacuum Pumps'
                ]
            ],
            [
                'name' => 'Other',
                'description' => 'Miscellaneous products and services',
                'subcategories' => [
                    'Apparel',
                    'Medical',
                    'Marine',
                    'Signs',
                    'Point Of Purchase Displays'
                ]
            ],
            [
                'name' => 'Services',
                'description' => 'Various industrial services and maintenance',
                'subcategories' => [
                    'Pump Repair Services',
                    'Machinery Rebuilders',
                    'Boiler Renting',
                    'Spindle Rebuilding & Repairing',
                    'Advertising Novelties & Specialties'
                ]
            ]
        ];

        foreach ($categories as $categoryData) {
            // Create main category
            $category = Category::firstOrCreate(
                ['name' => $categoryData['name']],
                [
                    'description' => $categoryData['description'],
                    'is_active' => true,
                    'parent_category_id' => null
                ]
            );

            // Create subcategories
            foreach ($categoryData['subcategories'] as $subcategoryName) {
                Category::firstOrCreate(
                    [
                        'name' => $subcategoryName,
                        'parent_category_id' => $category->id
                    ],
                    [
                        'description' => "Subcategory under {$categoryData['name']}",
                        'is_active' => true
                    ]
                );
            }
        }
    }
}

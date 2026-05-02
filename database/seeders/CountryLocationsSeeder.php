<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class CountryLocationsSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            'Australia' => [
                'New South Wales' => ['Sydney', 'Newcastle', 'Wollongong'],
                'Victoria' => ['Melbourne', 'Geelong', 'Ballarat'],
                'Queensland' => ['Brisbane', 'Gold Coast', 'Cairns'],
                'Western Australia' => ['Perth', 'Bunbury', 'Albany'],
                'South Australia' => ['Adelaide', 'Mount Gambier', 'Whyalla'],
                'Tasmania' => ['Hobart', 'Launceston', 'Devonport'],
                'Australian Capital Territory' => ['Canberra'],
                'Northern Territory' => ['Darwin', 'Alice Springs'],
            ],
            'New Zealand' => [
                'Auckland' => ['Auckland', 'Manukau'],
                'Wellington' => ['Wellington', 'Lower Hutt'],
                'Canterbury' => ['Christchurch', 'Timaru'],
            ],
            'USA' => [
                'Alabama' => ['Birmingham'],
                'Alaska' => ['Anchorage'],
                'Arizona' => ['Phoenix'],
                'Arkansas' => ['Little Rock'],
                'California' => ['Los Angeles', 'San Diego', 'San Francisco'],
                'Colorado' => ['Denver'],
                'Connecticut' => ['Bridgeport'],
                'Delaware' => ['Wilmington'],
                'Florida' => ['Miami'],
                'Georgia' => ['Atlanta'],
                'Hawaii' => ['Honolulu'],
                'Idaho' => ['Boise'],
                'Illinois' => ['Chicago'],
                'Indiana' => ['Indianapolis'],
                'Iowa' => ['Des Moines'],
                'Kansas' => ['Wichita'],
                'Kentucky' => ['Louisville'],
                'Louisiana' => ['New Orleans'],
                'Maine' => ['Portland'],
                'Maryland' => ['Baltimore'],
                'Massachusetts' => ['Boston'],
                'Michigan' => ['Detroit'],
                'Minnesota' => ['Minneapolis'],
                'Mississippi' => ['Jackson'],
                'Missouri' => ['Kansas City'],
                'Montana' => ['Billings'],
                'Nebraska' => ['Omaha'],
                'Nevada' => ['Las Vegas'],
                'New Hampshire' => ['Manchester'],
                'New Jersey' => ['Newark'],
                'New Mexico' => ['Albuquerque'],
                'New York' => ['New York City', 'Buffalo', 'Rochester'],
                'North Carolina' => ['Charlotte'],
                'North Dakota' => ['Fargo'],
                'Ohio' => ['Columbus'],
                'Oklahoma' => ['Oklahoma City'],
                'Oregon' => ['Portland'],
                'Pennsylvania' => ['Philadelphia'],
                'Rhode Island' => ['Providence'],
                'South Carolina' => ['Charleston'],
                'South Dakota' => ['Sioux Falls'],
                'Tennessee' => ['Nashville'],
                'Texas' => ['Houston', 'Dallas', 'Austin'],
                'Utah' => ['Salt Lake City'],
                'Vermont' => ['Burlington'],
                'Virginia' => ['Virginia Beach'],
                'Washington' => ['Seattle'],
                'West Virginia' => ['Charleston'],
                'Wisconsin' => ['Milwaukee'],
                'Wyoming' => ['Cheyenne'],
            ],
            'China' => [
                'Beijing' => ['Beijing'],
                'Tianjin' => ['Tianjin'],
                'Shanghai' => ['Shanghai'],
                'Chongqing' => ['Chongqing'],
                'Hebei' => ['Baoding', 'Cangzhou', 'Handan', 'Shijiazhuang', 'Tangshan', 'Xingtai'],
                'Jilin' => ['Changchun'],
                'Hunan' => ['Changsha', 'Hengyang', 'Shaoyang'],
                'Sichuan' => ['Chengdu'],
                'Guangdong' => ['Dongguan', 'Donguan', 'Foshan', 'Guangzhou/Canton', 'Shenzhen', 'Zhanjiang'],
                'Anhui' => ['Fuyang', 'Hefei'],
                'Fujian' => ['Fuzhou', 'Quanzhou'],
                'Jiangxi' => ['Ganzhou'],
                'Zhejiang' => ['Hangzhou', 'Ningbo', 'Wenzhou', 'Yiwu'],
                'Heilongjiang' => ['Harbin'],
                'Shandong' => ['Heze', 'Jinan', 'Jining', 'Linyi', 'Qingdao', 'Weifang'],
                'Hubei' => ['Huanggang', 'Wuhan'],
                'Jiangsu' => ['Nanjing', 'Nantong', 'Suzhou', 'Xuzhou'],
                'Guangxi' => ['Nanning', 'Yulin'],
                'Henan' => ['Nanyang', 'Shangqiu', 'Zhengzhou', 'Zhoukou', 'Zhumadian'],
                'Shaanxi' => ["Xi'an"],
                'Liaoning' => ['Shenyang'],
            ],
            'Germany' => [
                'Bavaria' => ['Munich', 'Nuremberg'],
                'Berlin' => ['Berlin'],
                'North Rhine-Westphalia' => ['Cologne', 'Dusseldorf'],
            ],
            'Japan' => [
                'Tokyo' => ['Tokyo'],
                'Osaka' => ['Osaka', 'Sakai'],
                'Kanagawa' => ['Yokohama', 'Kawasaki'],
            ],
            'United Kingdom' => [
                'England' => ['London', 'Manchester', 'Birmingham'],
                'Scotland' => ['Edinburgh', 'Glasgow'],
                'Wales' => ['Cardiff', 'Swansea'],
                'Northern Ireland' => ['Belfast', 'Derry'],
            ],
            'France' => [
                'Ile-de-France' => ['Paris', 'Boulogne-Billancourt'],
                'Auvergne-Rhone-Alpes' => ['Lyon', 'Grenoble'],
                'Provence-Alpes-Cote dAzur' => ['Marseille', 'Nice'],
            ],
            'Italy' => [
                'Lombardy' => ['Milan', 'Bergamo'],
                'Lazio' => ['Rome', 'Latina'],
                'Campania' => ['Naples', 'Salerno'],
            ],
            'Brazil' => [
                'Sao Paulo' => ['Sao Paulo', 'Campinas'],
                'Rio de Janeiro' => ['Rio de Janeiro', 'Niteroi'],
                'Minas Gerais' => ['Belo Horizonte', 'Uberlandia'],
            ],
            'Canada' => [
                'Ontario' => ['Toronto', 'Ottawa', 'Hamilton'],
                'Quebec' => ['Montreal', 'Quebec City'],
                'British Columbia' => ['Vancouver', 'Victoria'],
            ],
            'Russia' => [
                'Moscow' => ['Moscow'],
                'Saint Petersburg' => ['Saint Petersburg'],
                'Sverdlovsk Oblast' => ['Yekaterinburg'],
            ],
            'South Korea' => [
                'Seoul' => ['Seoul'],
                'Gyeonggi-do' => ['Suwon', 'Yongin'],
                'Busan' => ['Busan'],
            ],
            'Spain' => [
                'Community of Madrid' => ['Madrid'],
                'Catalonia' => ['Barcelona', 'Girona'],
                'Andalusia' => ['Seville', 'Malaga'],
            ],
            'Turkiye' => [
                'Istanbul' => ['Istanbul'],
                'Ankara' => ['Ankara'],
                'Izmir' => ['Izmir'],
            ],
            'Singapore' => [
                'Singapore' => ['Singapore'],
            ],
            'Saudi Arabia' => [
                'Riyadh Province' => ['Riyadh'],
                'Makkah Province' => ['Jeddah', 'Mecca'],
                'Eastern Province' => ['Dammam', 'Khobar'],
            ],
            'Thailand' => [
                'Bangkok' => ['Bangkok'],
                'Chiang Mai' => ['Chiang Mai'],
                'Phuket' => ['Phuket'],
            ],
            'Sweden' => [
                'Stockholm County' => ['Stockholm'],
                'Vastra Gotaland County' => ['Gothenburg'],
                'Skane County' => ['Malmo'],
            ],
            'Ireland' => [],
            'India' => [
                'Maharashtra' => ['Mumbai', 'Pune'],
                'Karnataka' => ['Bengaluru', 'Mysuru'],
                'Tamil Nadu' => ['Chennai', 'Coimbatore'],
            ],
            'Malaysia' => [
                'Selangor' => ['Shah Alam', 'Petaling Jaya'],
                'Johor' => ['Johor Bahru'],
                'Penang' => ['George Town'],
            ],
            'Argentina' => [
                'Buenos Aires Province' => ['La Plata', 'Mar del Plata'],
                'Cordoba' => ['Cordoba'],
                'Santa Fe' => ['Rosario'],
            ],
            'Austria' => [
                'Vienna' => ['Vienna'],
                'Upper Austria' => ['Linz'],
                'Styria' => ['Graz'],
            ],
            'Azerbaijan' => [
                'Baku' => ['Baku'],
                'Ganja-Dashkasan' => ['Ganja'],
                'Lankaran-Astara' => ['Lankaran'],
            ],
            'Bangladesh' => [
                'Dhaka Division' => ['Dhaka', 'Gazipur'],
                'Chattogram Division' => ['Chattogram', 'Coxs Bazar'],
                'Khulna Division' => ['Khulna'],
            ],
        ];

        foreach ($locations as $countryName => $statesWithCities) {
            foreach ($statesWithCities as $stateName => $cities) {
                $state = State::updateOrCreate(
                    ['name' => $stateName, 'country_name' => $countryName],
                    ['name' => $stateName, 'country_name' => $countryName]
                );

                foreach ($cities as $cityName) {
                    $state->cities()->updateOrCreate(
                        ['name' => $cityName],
                        ['name' => $cityName]
                    );
                }
            }
        }
    }
}

<?php

namespace Webkul\Seller\Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class AreasTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('areas')->delete();


$areas= [
     "Kuwait City مدينة الكويت",
     "Dasmān دسمان",
     "Sharq شرق",
     "Mirgāb المرقاب",
     "Jibla جبلة",
     "Dasma الدسمة",
     "Da'iya الدعية",
     "Sawābir الصوابر",
     "Salhiya الصالحية",
     "Bneid il-Gār بنيد القار",
     "Kaifan كيفان",
     "Mansūriya المنصورية",
     "Abdullah as-Salim suburb ضاحية عبد الله السالم",
     "Nuzha النزهة",
     "Faiha' الفيحاء",
     "Shamiya الشامية",
     "Rawda الروضة",
     "Adiliya العديلية",
     "Khaldiya الخالدية",
     "Qadsiya القادسية",
     "Qurtuba قرطبة",
     "Surra السرة",
     "Yarmūk اليرموك",
     "Shuwaikh الشويخ",
     "Rai الري",
     "Ghirnata غرناطة",
     "Sulaibikhat الصليبخات",
     "Doha الدوحة",
     "Nahdha النهضة",
     "Jabir al-Ahmad City مدينة جابر الأحمد",
     "Qairawān القيروان",
     "Hawally حولي",
     "Rumaithiya الرميثية",
     "Jabriya الجابرية",
     "Salmiya السالمية",
     "Mishrif مشرف",
     "Sha'ab الشعب",
     "Bayān بيان",
     "Bi'di' البدع",
     "Nigra النقرة",
     "Salwa سلوى",
     "Maidan Hawalli ميدان حولي",
     "Mubarak aj-Jabir suburb ضاحية مبارك الجابر",
     "South Surra جنوب السرة",
     "Hittin حطين",
     "Mubarak al-Kabeer مبارك الكبير",
     "Adān العدان",
     "Qurain القرين",
     "Qusūr القصور",
     "Sabah as-Salim suburb ضاحية صباح السالم",
     "Misīla المسيلة",
     "Abu 'Fteira أبو فطيرة",
     "Sabhān صبحان",
     "Fintās الفنطاس",
     "Funaitīs الفنيطيس",
     "Ahmadi الأحمدي",
     "Aqila العقيلة",
     "Zuhar الظهر",
     "Miqwa' المقوع",
     "Mahbula المهبولة",
     "Rigga الرقة",
     "Hadiya هدية",
     "Abu Hulaifa أبو حليفة",
     "Sabahiya الصباحية",
     "Mangaf المنقف",
     "Fahaheel الفحيحيل",
     "Wafra الوفرة",
     "Zoor الزور",
     "Khairan الخيران",
     "Abdullah Port ميناء عبد الله",
     "Agricultural Wafra الوفرة الزراعية",
     "Bneidar بنيدر",
     "Jilei'a الجليعة",
     "Jabir al-Ali Suburb ضاحية جابر العلي",
     "Fahd al-Ahmad Suburb ضاحية فهد الأحمد",
     "Shu'aiba الشعيبة",
     "Sabah al-Ahmad City مدينة صباح الأحمد",
     "Nuwaiseeb النويصيب",
     "Khairan City مدينة الخيران",
     "Ali as-Salim suburb ضاحية علي صباح السالم",
     "Sabah al-Ahmad Nautical City مدينة صباح الأحمد البحرية",
    ];

        foreach ($areas as $key=> $area) {
            DB::table('areas')->insert([
                'id' => $key + 1,
                'name' => $area
            ]);
        }
    }
}

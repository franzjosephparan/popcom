<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = new Category();
        $category->category_name = 'Progestin Only Pills (POP) Pills';
        $category->save();

        $category2 = new Category();
        $category2->category_name = 'Combined Oral Contraceptive (COC) Pill';
        $category2->save();

        $category3 = new Category();
        $category3->category_name = 'DMPA (Injectable)';
        $category3->save();

        $category4 = new Category();
        $category4->category_name = 'Porgestin Sub-Dermal Implant (PSI)';
        $category4->save();

        $category5 = new Category();
        $category5->category_name = 'Intrauterine Device (IUD)';
        $category5->save();

        $category6 = new Category();
        $category6->category_name = 'Male Condom';
        $category6->save();

        $category7 = new Category();
        $category7->category_name = 'Female Condom';
        $category7->save();
    }
}

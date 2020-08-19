<?php

use Illuminate\Database\Seeder;
use App\Item;

class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $item = new Item();
        $item->item_sku = 'POPP';
        $item->item_name = 'Progestin Only Pills (POP) Pills';
        $item->item_description = 'Progestin Only Pills (POP) Pills';
        $item->category = 1;
        $item->status = 1;
        $item->created_by = 1;
        $item->updated_by = 1;
        $item->save();

        $item2 = new Item();
        $item2->item_sku = 'COCP';
        $item2->item_name = 'Combined Oral Contraceptive (COC) Pills';
        $item2->item_description = 'Combined Oral Contraceptive (COC) Pills';
        $item2->category = 2;
        $item2->status = 1;
        $item2->created_by = 1;
        $item2->updated_by = 1;
        $item2->save();

        $item3 = new Item();
        $item3->item_sku = 'DMPAI';
        $item3->item_name = 'DMPA (Injectable)';
        $item3->item_description = 'DMPA (Injectable)';
        $item3->category = 3;
        $item3->status = 1;
        $item3->created_by = 1;
        $item3->updated_by = 1;
        $item3->save();

        $item4 = new Item();
        $item4->item_sku = 'PSDI';
        $item4->item_name = 'Porgestin Sub-Dermal Implant (PSI)';
        $item4->item_description = 'Porgestin Sub-Dermal Implant (PSI)';
        $item4->category = 4;
        $item4->status = 1;
        $item4->created_by = 1;
        $item4->updated_by = 1;
        $item4->save();

        $item5 = new Item();
        $item5->item_sku = 'IDIUD';
        $item5->item_name = 'Intrauterine Device (IUD)';
        $item5->item_description = 'Intrauterine Device (IUD)';
        $item5->category = 5;
        $item5->status = 1;
        $item5->created_by = 1;
        $item5->updated_by = 1;
        $item5->save();

        $item6 = new Item();
        $item6->item_sku = 'MACOM';
        $item6->item_name = 'Male Condom';
        $item6->item_description = 'Male Condom';
        $item6->category = 6;
        $item6->status = 1;
        $item6->created_by = 1;
        $item6->updated_by = 1;
        $item6->save();

        $item7 = new Item();
        $item7->item_sku = 'FECOM';
        $item7->item_name = 'Female Condom';
        $item7->item_description = 'Female Condom';
        $item7->category = 7;
        $item7->status = 1;
        $item7->created_by = 1;
        $item7->updated_by = 1;
        $item7->save();
    }
}

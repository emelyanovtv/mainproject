<?php
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Pingpong\Admin\Entities\StorageEvents;
use Pingpong\Admin\Entities\StorageEventsMaterials;
use Pingpong\Admin\Entities\StorageHasEventsMaterials;
use Pingpong\Admin\Entities\StorageHasMaterial;
use Pingpong\Admin\Entities\Storages;

class HomeController extends BaseController {


	public function showWelcome()
	{
        $list = Storages::with(
                'hasMaterials.materials.materialsgroup'

        )->get();
        $materialConfig = Config::get('app.materialsConfig');
        $dataArr = [];
        foreach($list as $storage)
        {
            $dataArr[$storage->name] = [];
            if(count($storage->hasMaterials))
            {
                foreach($storage->hasMaterials as $item)
                {
                    if(in_array($item->materials->material_group_id,$materialConfig['group_ids']) && array_key_exists($item->materials->id, $materialConfig['materials']))
                    {
                        $dataArr[$storage->name][] = $item;
                    }

                }
            }
        }
        return View::make('pages.hello', compact('dataArr'));
	}

}

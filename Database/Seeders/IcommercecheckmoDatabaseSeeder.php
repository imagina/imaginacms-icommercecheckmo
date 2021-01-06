<?php

namespace Modules\Icommercecheckmo\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Icommerce\Entities\PaymentMethod;

class IcommercecheckmoDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $methods = config('asgard.icommercecheckmo.config.methods');

        if(count($methods)>0){

            $init = "Modules\Icommercecheckmo\Http\Controllers\Api\IcommerceCheckmoApiController";

            foreach ($methods as $key => $method) {

                $result = PaymentMethod::where('name',$method['name'])->first();

                if(!$result){

                    $options['init'] = $init;

                    $options['mainimage'] = null;
                    $options['minimunAmount'] = 0;

                    $titleTrans = $method['title'];
                    $descriptionTrans = $method['description'];

                    foreach (['en', 'es'] as $locale) {

                        if($locale=='en'){
                            $params = array(
                                'title' => trans($titleTrans),
                                'description' => trans($descriptionTrans),
                                'name' => $method['name'],
                                'status' => $method['status'],
                                'options' => $options
                            );

                            $paymentMethod = PaymentMethod::create($params);
                            
                        }else{

                            $title = trans($titleTrans,[],$locale);
                            $description = trans($descriptionTrans,[],$locale);

                            $paymentMethod->translateOrNew($locale)->title = $title;
                            $paymentMethod->translateOrNew($locale)->description = $description;

                            $paymentMethod->save();
                        }

                    }// Foreach

                }else{
                     $this->command->alert("This method: {$method['name']} has already been installed !!");
                }
            }
        }else{
           $this->command->alert("No methods in the Config File !!"); 
        }
 
    }
}

<?php

namespace Database\Seeders;

use App\Models\DeviceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeviceTypeImpactSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run()
    {
        $types = [
            '3349056e-2c68-4a51-a30c-91c5fed8c033' =>   ['Desktop computer', 8.868136364, 435.8735455],
            '00c02a84-f5c5-4693-9376-58f766f6195e' =>   ['Kleine laptop', 1.322222222, 221.1522667],
            'c64105bb-6322-4cfa-abca-e38faca0b43f' =>   ['Laptop groot',2.534745763, 414.6973333],
            '63ed5d48-a85f-4f95-883c-7cf09819fe54' =>   ['Flatscreen 15-17',3.147, 262.5575],
            '5e4a79d4-858e-405a-8278-42463b8d1e8a' =>   ['Flatscreen 19-20',3.662746154, 304.8757333],
            '5d693262-6838-4776-b424-9f14d3d751aa' =>   ['Flatscreen 22-24',5.228440541, 343.4929444],
            '94c5c6a0-2cd1-4561-a157-2a3c81ec8234' =>   ['Digitale camera',0.4826, 29.726],
            '02cc8e70-aba5-4e55-9d5b-8d592dd0944f' =>   ['DSLR + Video camera',0.585, 16.025],
            '9608b20e-95c9-4336-a0db-ba9d7d0d2930' =>   ['Draagbaar entertainment device',0.1369714286, 22.70666667],
            '0bc6641d-26f9-4c44-81af-8762adb574c8' =>   ['Hoofdtelefoon',0.4, 3],
            'f950a971-f031-4abf-9591-5d4ade44e74b' =>   ['Mobiele telefoon',0.1514615385, 51.05581081],
            '1aec832e-2bfd-42a9-87d2-ef5df310ac1c' =>   ['Tablet of e-reader',0.773375, 114.4902651],
            'f217dd2f-9031-4170-ad9e-ef6b4d0c7e6b' =>   ['Flatscreen 26-30',4.2146, 388.788],
            '0dcdac41-6f84-4b08-9a01-890275524c06' =>   ['Flatscreen 32-37',14.11472222, 349.575],
            'f28c2763-5f51-4ba7-b8f9-02f6e5b79c1e' =>   ['Hifi geïntegreerd',8.95,186.5],
            'd41801a6-e141-4aa4-8215-d9830684cff8' =>   ['Hifi accessoires',8.72, 123.86],
            'd18a6d23-1f5b-47c8-b565-a867992979d3' =>   ['TV of game gerelateerd accessoire',1.98925,51.3332],
            '39b61ee1-7703-4089-b471-41ab2dcedf92' =>   ['Muziekinstrument',17.7, 0],
            '1b0c2752-8419-47d4-b000-be23d6b2e20d' =>   ['Draagbare radio',2.055, 54.65],
            '773028e7-6881-4d19-aff7-5c71861fbc6d' =>   ['Projector',2.678,57.57666667],
            '94090ea5-80a6-426d-a0a9-e7242c42b259' =>   ['Airco of luchtontvochtiger',7.6, 99.40666667],
            '702a248b-83e4-4d6e-b84c-399d8e211571' =>   ['Decoratieve of veiligheidsverlichting',0.4575,14.715],
            '4656982c-1187-41c9-8803-a0fafec47844' =>   ['Ventilator',3.8954,15.6],
            '704561d1-eed5-45d3-b360-18665988829d' =>   ['Haar/schoonheidsitem',0.672,10.28],
            '5cdda5d0-349b-4d6b-adea-8238006f63ac' =>   ['Waterkoker',1.2454,44.32],
            '43414e93-eea2-4536-9702-c0c11fb968f7' =>   ['Lamp',2.6806,14.964],
            '7fe760d2-1221-48bd-94de-5655f478bc38' =>   ['Elektrisch gereedschap',1.65,26.65],
            'e52fb79e-f9e5-4da1-953c-4f7854c2a623' =>   ['Klein keukenelektro',1.53,37.20095],
            '0e57622f-e27f-4afa-8588-9590a622361d' =>   ['Broodrooster',1.465,8.125666667],
            'aed4b504-25f1-43e8-9c5c-73e2828fe014' =>   ['Speelgoed',0.9333333333,10.4],
            'e047ece8-6c33-4270-a41e-bd40fd1b967a' =>   ['Stofzuiger',6.800588235,36.01428571],
            'a298635f-e526-4185-9746-0f39cb6dfad5' =>   ['Papierversnipperaar',3.8,59.5],
            '43a87a28-9fc2-48a2-8f2b-e5d9851748f2' =>   ['PC Accessoire',0.435,15.9353952],
            '879e346c-0e53-4b4d-92fe-d47157b07e40' =>   ['Printer/scanner',12.4655,100.69],
            'f30db934-bd13-4620-9661-0ab1c6ab5c10' =>   ['Koffiemachine',1.28,17.5],
            '24d72caa-ba1d-42b0-b48c-7baeb652bbb2' =>   ['Spelconsole',1.98925,51.3332],
            '6465600c-39de-4057-9598-3ecb2eaf544e' =>   ['Blender/mixer',3.068333333,49.8],
            '2b16a416-b10b-4f13-b4ff-b67ec7366c52' =>   ['Strijkijzer',1.17,11],
            '89da7ad9-1de9-412c-bce7-a6f378d7b6cb' =>   ['Haardroger',0.672,10.28],
            'fcc62811-ed08-4ca3-8364-7e5713bd389c' =>   ['Batterijlader/adaptor',0.074665, 0.54843],
            '8e80c1d2-b8e7-4fcd-9458-f2cf8a8047af' =>   ['Groot huishoudelijk elektro',5.84,35.34],
            'b56880e2-79eb-4de5-a9ec-5b3969a9aeeb' =>   ['Naaimachine',8.6,35],
            'ece38a69-23f6-46a1-8484-b847980c10b2' =>   ['Uurwerk/klok',1.3,28.29071429],
            'b3ed58e0-1666-409c-80a7-606f2fa5444e' =>   ['Andere',0,0],
            '7a6c6436-6c82-42db-b52d-e083b2e08283' =>   ['Kledij & textiel',0.75,20.32272625],
            'b90c9098-52f7-49a0-875d-92d8c2c9c502' =>   ['Meubels',29.80769231,67.13076923],
            'a094ac28-064d-4e4c-b398-7e21c1a23d81' =>   ['Fiets',15.1,149.6],
            '3fb1c1a5-b9d0-42ad-a179-d4fdb8c1cd6d' =>   ['Broek',0.75,20.32272625],
            '1dd14bc7-aba3-4225-bd44-a3811bb5f3fd' =>   ['Kleed',0.75,20.32272625],
            '6b8096bd-be52-43f9-96a1-84d0f4059b09' =>   ['Rok',0.75,20.32272625],
            '302e3ef3-e318-4610-a023-c5b91f6d2a19' =>   ['Bloes / Hemd',0.75,20.32272625],
            '8a75e48c-274e-4cdc-a49c-1f20281290fd' =>   ['Jas',0.75,20.32272625],
            'ed1a4270-3fb9-4995-86a4-73bb0dc19882' =>   ['Bedlinnen',0.75,20.32272625],
            '7fbb183f-cfab-44dc-83b2-3b1e6e5605ff' =>   ['Ander textiel',0.75,20.32272625],
            '6bbff361-4187-4362-8f9d-3ab14faa8703' =>   ['Trui',0.75,20.32272625],
            '81e99aea-b3ef-469a-90d8-aa9547109e86' =>   ['Inktjetprinter',12.4655,100.69],
            '530fa21e-6ccb-48c5-b162-78fbdecf6c67' =>   ['Laserprinter',12.4655,100.69],
            '2959dd46-8b0b-415c-96ff-ae12e99d74c1' =>   ['Windows laptop',1.834364055,265.4804844],
            '917124c2-726e-4c20-8365-55fb30bb1da0' =>   ['Laserprinter',12.4655,100.69],
            'f943ff73-9cff-4fd9-bec9-37ca603607bc' =>   ['Smartphones Android',0.1514615385,51.05581081],
            '4afa99d1-f5a4-4aa0-bc77-304c6a01aacd' =>   ['Smartphone Apple',0.1514615385,51.05581081],
            '2d23c16d-cc40-444f-a827-750e85e9755d' =>   ['Wasmachine',1.53,37.20095],
            '40547855-f548-43b4-9710-e920f8d0a40e' =>   ['Laptop MacOS',1.834364055,265.4804844],
        ];


        foreach ($types as $index => $type) {
            $deviceType = DeviceType::where('uuid', $index)->first();
            $deviceType->product_weight_kg =$type[1];
            $deviceType->product_co_kg = $type[2];
            $deviceType->save();
        }
    }
}

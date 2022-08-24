<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PersonRepository;
use App\Helpers\WhatsappHelper;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PasienBaruController extends Controller
{
    public function index($type)
    {  
        $_title = 'Pendaftaran Baru';
        $_subtitle = 'Silahkan scan kartu identitas anda';
        $_backUrl = 'back';


        return view('pasien_baru.index', compact(
            '_title',
            '_subtitle',
            '_backUrl',
            'type'
        ));
    }

    public function register($type)
    {
        $_title = 'Pendaftaran Baru';
        $_subtitle = 'Silahkan isi atau lengkapi data identitas anda';
        $_backUrl = 'back';

        $countries = $this->getCountries();

        return view('pasien_baru.register', compact(
            '_title',
            '_subtitle',
            '_backUrl',
            'type',
            'countries'
        ));
    }

    public function save(Request $request, $type)
    {
        $pid                    = PersonRepository::nextPid();
        $payload                = $request->post();
        $payload['pid']         = $pid;
        $payload['modify_id']   = '1';
        $payload['modify_time'] = 'now()';
        $payload['create_id']   = '1';
        $payload['create_time'] = 'now()';

        unset($payload['_token']);

        try {
            DB::beginTransaction();
            $pid = PersonRepository::insert($payload);
            $person = PersonRepository::getField('*, format_rm(pid) as rm', $pid);

            $person->date_birth = Carbon::parse($person->date_birth)->format('d-m-Y');

            $waData = (object) [
                'rm' => $person->rm,
                'phone' => $person->mobile_nr1
            ];

            WhatsappHelper::sendWaPasienBaru($waData);
            DB::commit(); 
        } catch (Exception $e) {
            DB::rollback();
            return 'Gagal daftar pasien';
        }

        return view('pasien_baru.confirm', compact(
            'type',
            'person'
        ));
    }

    public function resend_wa($pid, $type)
    {
        $person = PersonRepository::getField('mobile_nr1, format_rm(pid) as rm', $pid);

        $waData = (object) [
            'rm' => $person->rm,
            'phone' => $person->mobile_nr1
        ];

        WhatsappHelper::sendWaPasienBaru($waData);

        return response()->json(array('success'=>true));
    }

    private function getCountries()
    {
        return [
           "Abkhazia",
           "Afganistan",
           "Afrika Selatan",
           "Republik Afrika Tengah",
           "Akrotiri dan Dhekelia",
           "�land",
           "Albania",
           "Aljazair",
           "Amerika Serikat",
           "Andorra",
           "Antillen_Belanda",
           "Angola",
           "Anguilla",
           "Antigua dan Barbuda",
           "Arab Saudi",
           "Argentina",
           "Armenia",
           "Aruba",
           "Ascension",
           "Australia",
           "Austria",
           "Azerbaijan",
           "Bahama",
           "Bahrain",
           "Bangladesh",
           "Barbados",
           "Belanda",
           "Belarus",
           "Belgia",
           "Belize",
           "Benin",
           "Bermuda",
           "Bhutan",
           "Bolivia",
           "Bosnia-Herzegovina",
           "Botswana",
           "Brasil",
           "Britania Raya",
           "Brunei",
           "Bulgaria",
           "Burkina Faso",
           "Burundi",
           "Chad",
           "Kepulauan Cayman",
           "Republik Ceko",
           "Chili",
           "Republik China (Taiwan)",
           "Kepulauan Cook",
           "Cina (lihat Republik Rakyat Tiongkok)",
           "Kepulauan Cocos (Keeling)",
           "C�te d'Ivoire (Pantai Gading)",
           "Denmark",
           "Djibouti",
           "Dominika",
           "Republik Dominika",
           "Ekuador",
           "El Salvador",
           "Eritrea",
           "Estonia",
           "Ethiopia",
           "Fiji",
           "Filipina",
           "Finlandia",
           "Kepulauan Falkland",
           "Kepulauan Faroe",
           "Gabon",
           "Gambia",
           "Jalur Gaza (lihat Palestina)",
           "Georgia",
           "Ghana",
           "Gibraltar",
           "Grenada",
           "Greenland",
           "Guatemala",
           "Guadeloupe",
           "Guinea",
           "Guinea Bissau",
           "Guinea Khatulistiwa",
           "Guam",
           "Guernsey",
           "Guyana",
           "Guyana Perancis",
           "Haiti",
           "Honduras",
           "Hong Kong",
           "Hongaria",
           "India",
           "Indonesia",
           "Inggris (lihat Britania Raya)",
           "Irak",
           "Iran",
           "Republik Irlandia",
           "Irlandia Utara",
           "Islandia",
           "Israel",
           "Italia",
           "Jamaika",
           "Jepang",
           "Jerman",
           "Jersey",
           "Kamboja",
           "Kamerun",
           "Kanada",
           "Kaledonia Baru",
           "Kazakhstan",
           "Kenya",
           "Kirgizstan",
           "Kiribati",
           "Kolombia",
           "Komoro",
           "Republik Kongo",
           "Republik Demokratik Kongo",
           "Korea Selatan",
           "Korea Utara",
           "Kosta Rika",
           "Kroasia",
           "Kuba",
           "Kuwait",
           "Laos",
           "Latvia",
           "Lebanon",
           "Lesotho",
           "Liberia",
           "Libya",
           "Liechtenstein",
           "Lituania",
           "Luksemburg",
           "Makau",
           "Madagaskar",
           "Maladewa",
           "Malawi",
           "Malaysia",
           "Mali",
           "Malta",
           "Republik Makedonia",
           "Pulau Man",
           "Kepulauan Mariana Utara",
           "Maroko",
           "Martinique (Martinik/Martinika)",
           "Kepulauan Marshall",
           "Mauritania",
           "Mauritius",
           "Mayotte",
           "Meksiko",
           "Mesir",
           "Federasi Mikronesia",
           "Moldova",
           "Rep. Moldavia Pridnestrovian (lihat Transnistria)",
           "Monako",
           "Mongolia",
           "Montenegro",
           "Montserrat",
           "Mozambik",
           "Myanmar",
           "Nagorno-Karabakh",
           "Namibia",
           "Pulau Natal",
           "Nauru",
           "Nepal",
           "Nikaragua",
           "Niger",
           "Nigeria",
           "Niue",
           "Norwegia",
           "Pulau Norfolk",
           "Oman",
           "Ossetia Selatan",
           "Pakistan",
           "Palau",
           "Otoritas Nasional Palestina: Tepi Barat dan Jalur Gaza",
           "Panama",
           "Pantai Gading",
           "Papua Nugini",
           "Paraguay",
           "Perancis",
           "Peru",
           "Polandia",
           "Polinesia Perancis",
           "Portugal",
           "Puerto Riko",
           "Pitcairn",
           "Qatar",
           "R�union",
           "Rumania",
           "Federasi Rusia",
           "Rwanda",
           "Sahara Barat",
           "Saint Helena",
           "Saint Kitts dan Nevis",
           "Saint Lucia",
           "Saint-Pierre dan Miquelon",
           "Saint Vincent dan Grenadines",
           "San Marino",
           "Samoa",
           "Sao Tome dan Principe",
           "Selandia Baru",
           "Senegal",
           "Serbia",
           "Seychelles",
           "Sierra Leone",
           "Singapura",
           "Siprus",
           "Republik Turki Siprus Utara",
           "Skotlandia (lihat Britania Raya)",
           "Slowakia",
           "Slovenia",
           "Solomon",
           "Somalia",
           "Somaliland",
           "Spanyol",
           "Sri Lanka",
           "Sudan",
           "Suriah",
           "Suriname",
           "Svalbard",
           "Swaziland",
           "Swedia",
           "Swiss",
           "Taiwan (lihat Republik China)",
           "Tajikistan",
           "Tanjung Verde",
           "Tanzania",
           "Tepi Barat (lihat Palestina)",
           "Thailand",
           "Timor Timur",
           "Republik Rakyat Tiongkok (RRT)",
           "Togo",
           "Tokelau",
           "Tonga",
           "Transnistria",
           "Tristan da Cunha",
           "Trinidad dan Tobago",
           "Tunisia",
           "Turki",
           "Turkmenistan",
           "Tuvalu",
           "Kepulauan Turks dan Caicos",
           "Uganda",
           "Ukraina",
           "Uni Emirat Arab",
           "Uruguay",
           "Uzbekistan",
           "Vanuatu",
           "Vatikan",
           "Venezuela",
           "Vietnam",
           "Kepulauan Virgin Britania Raya",
           "Kepulauan Virgin Amerika Serikat",
           "Wales",
           "Wallis dan Futuna",
           "Yaman",
           "Yordania(Jordania)",
           "Yunani",
           "Zambia",
           "Zimbabwe",
        ];
    }
}

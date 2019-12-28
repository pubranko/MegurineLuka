<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Member;                                                #追加
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MembersTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
    public function testMember(){
        $response = $this->get('/member/registerin');
        $response->assertStatus(200);

        $member = factory(Member::class,2)->create(); //Memberモデルクラスを、factory(※~/database/factores/MemberFactory)に元々定義されていたメソッドに渡してインスタンスにしているっぽい。
        #$response = $this->actingAs($member)->get('/member');  #これでログインした状態でgetとなる。

        #?_token=Fekx2vxPJDaAeR8ycGcSHjVsL5RldqfJT4dQ5ePB&email=mikuras2%40outlook8.com&last_name=半田&first_name=祐二&last_name_kana=ハンダ&first_name_kana=ユウジ&birthday_era=西暦&birthday_year=1977&birthday_month=04&birthday_day=11&sex=男性&postal_code1=340&postal_code2=0035&address1=埼玉県&address2=草加市&address3=西町&address4=７６５−５&address5=セフィラ西&address6=２０３&phone_number1=090&phone_number2=9394&phone_number3=4781
        #エラーデータ（入力漏れ）
        $response = $this->get('/member/registercheck?email=mikuras2@outlook8.com&last_name=半田&first_name=祐二&last_name_kana=ハンダ&first_name_kana=ユウジ&birthday_era=西暦&birthday_year=1977&birthday_month=04&birthday_day=11&sex=男性&postal_code1=340&postal_code2=0035&address1=埼玉県&address2=草加市&address3=西町&address4=７６５−５&address5=セフィラ西&address6=２０３&phone_number1=090&phone_number2=9394&phone_number3=');
        $response->assertStatus(302);
        #正常データ
        $response = $this->get('/member/registercheck?email=mikuras2@outlook8.com&last_name=半田&first_name=祐二&last_name_kana=ハンダ&first_name_kana=ユウジ&birthday_era=西暦&birthday_year=1977&birthday_month=04&birthday_day=11&sex=男性&postal_code1=340&postal_code2=0035&address1=埼玉県&address2=草加市&address3=西町&address4=７６５−５&address5=セフィラ西&address6=２０３&phone_number1=090&phone_number2=9394&phone_number3=4781');
        $response->assertStatus(200);

        #$response = $this->post('/member/register');
        #$response->assertStatus(200);

    }
}

<?

include("./header.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // HTML form에서 전송된 데이터를 받음
    $acc_kor = $_REQUEST["acc_kor"];
    $sheet_name = $_REQUEST["sheet_name"];
}


require __DIR__ . '/vendor/autoload.php';

use Google_Client;
use Google_Service_Sheets;

// Google API 클라이언트 생성
$client = new Google_Client();

$client->setApplicationName('accwebzine');
$client->setScopes([Google_Service_Sheets::SPREADSHEETS_READONLY]);


$client->setAuthConfig('./json/accwebzin-9317fa109544.json');  // API 인증 정보 경로


// Google Sheets 서비스 생성
$service = new Google_Service_Sheets($client);

// 스프레드시트 ID
$spreadsheetId = '1BArKcEjE06zMss3cbbN2DGvfff0Vf9g_1fHrKNNr8t0';

if ($sheet_name) :

    // 읽어올 데이터의 범위 (A 열과 B 열 예제)
    $range = $sheet_name.'!B:C';

    try {
        // 스프레드시트 데이터 가져오기
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();

        // 한글 내용과 대치되는 영문 내용 배열
        $replacementArray = [];

        // 데이터를 읽어와서 배열에 저장
        foreach ($values as $row) {
            $koreanContent = trim($row[0]);
            $englishContent = trim($row[1]);
            $replacementArray[$koreanContent] = $englishContent;
            //echo $koreanContent.'<br/>';
        }

        // 길이가 긴 문장부터 대체
        uksort($replacementArray, function($a, $b) {
            return strlen($b) - strlen($a);
        });

        $eng_content_tag = str_replace(array_keys($replacementArray), array_values($replacementArray),$acc_kor);
    } catch (Google_Service_Exception $e) {
        $no_sheet = '시트 명이 존재하지 않습니다.';
    }
endif;

?>

<textarea id="copyCode"></textarea>
<div class="ViewPage">
    <div class="content container article_wrap">
        <h2>콘텐츠 한 &gt; 영 번역 적용 (구글시트 연동)</h2>
        <section id="submn01">
            <article>
                <p>국문 태그 입력</p>
                <form action="content_trans.php" method="post">
                    <textarea name="acc_kor" id="acc_kor" style="height:300px"><?=(strlen($acc_kor) > 0) ? htmlspecialchars($acc_kor) : ''; ?></textarea>
                    <div style="margin-top:10px; display:flex; flex-wrap:wrap; gap:10px; justify-content:space-between">
                        <div class="trans">
                            <a href="https://docs.google.com/spreadsheets/d/<?=$spreadsheetId; ?>" target="_blank" rel="noopener noreferrer">콘텐츠 번역 시트 <i class="fas fa-external-link-alt"></i></a>
                        </div>
                        <div class="btn_acc_trans">
                            <label for="sheet_name">시트명</label>&nbsp;
                            <input type="text" name="sheet_name" id="sheet_name" size="10" value="<?=$sheet_name ? $sheet_name : ''; ?>" />
                            <button type="submit" class="acc_translate">영문 변환</button>
                        </div>
                    </div>
                </form>
            <? if ($no_sheet) : ?>
                <p><?=$no_sheet; ?></p>
            <? elseif ($eng_content_tag) : ?>
                <p>영문 태그 출력</p>
                <button class="copy"><span></span></button>
            <pre class="prettyprint lang-css" style="height:400px; overflow-y:scroll">
<?=htmlspecialchars($eng_content_tag); ?>
</pre>
            <? endif; ?>
            </article>
        </section>
        <section id="submn02">
            <h4>사용 매뉴얼</h4>
            <article class="acc_manual">
                <p>1. 대체할 콘텐츠 내용을 연동된 구글 스프레드 시트에 적용</p>
                <div><span>1</span> 워드 파일의 번역 내용을 Ctrl + A 로 전체선택 후 복사</div>
                <div><span>2</span> 스프레드 시트의 시트 추가 버튼을 이용하여 새 시트 추가, 혹은 기존 시트 복사</div>
                <div><span>3</span> A1열 클릭 후 붙여넣기 및 폰트 사이즈 조정</div>
                
                <p>2. 국문 콘텐츠 퍼블리싱 태그 입력</p>
                <div><span>1</span> 적용할 시트명<small style="color:#999;">(※구글스프레드시트의 하단 탭)</small> 입력 후 영문 변환 클릭</div>
                <div>
                    <span>2</span> 출력된 영문 변환 태그를 복사하여 사용
                </div>
            </article>
        </section>
    </div><!-- content container  -->
</div>

<? include("./footer.php"); ?>
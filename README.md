# 구글 스프레드시트를 이용한 한 -> 영 번역 적용


## 1. 구글 API 클라이언트 생성


## 2. 구글 스프레드 시트 ID 연결

content_trans.php

```
// 스프레드시트 ID
$spreadsheetId = '연결할 스프레드시트 ID';
```


## 3. 사용 매뉴얼
1. 대체할 콘텐츠 내용을 연동된 구글 스프레드 시트에 적용
   * 워드 파일의 번역 내용을 Ctrl + A 로 전체선택 후 복사
   * 스프레드 시트의 시트 추가 버튼을 이용하여 새 시트 추가, 혹은 기존 시트 복사
   * A1열 클릭 후 붙여넣기 및 폰트 사이즈 조정


2. 국문 콘텐츠 퍼블리싱 태그 입력
   * 적용할 시트명(※구글스프레드시트의 하단 탭) 입력 후 영문 변환 클릭
   * 출력된 영문 변환 태그를 복사하여 사용

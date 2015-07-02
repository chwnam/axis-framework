# Ver 0.21.0000
2015년 07월 01일
entity model 의 join 구현 issue

2015년 06월 30일
short-style context 구조 마련. 기존의 경우 모든 context가 명시적으로 클래스 선언이 이루어져야 했으나, short style 식으로 작성하면
 기존 워드프레스 api와 매우 유사하면서도 control 객체를 활용 가능한 형태로 코드 전개가 가능하다. 
 
2015년 06월 24일
* 전반적인 구조 변경
  * 복수형 namespace, 디렉토리 구조를 모두 단수화 변경 (피바람...)
  * 이 대형 변경으로 인해 판올림

# Ver 0.20.1000
2015년 06월 24일
* form object 기능 고찰
* simple view 삭제
* 모듈 오토로딩 기능 추가

2015년 06월 21일
* form object 기능 단순하게 구현
  * 모델이 없는 form 요소에 대해 구조를 알려주고 sanitize, nonce validation 보다 편하게 하는 기능
* loader
  * initial override 제대로 안 되던 문제 수정
  * generic_view() 함수 도입: 단순히 콘텍스트와 템플릿만 부르는 용도
* control
  * render_template() 함수: 컨트롤러에서 사용. generic view 사용
* context
  * context_callback(), add_context_action(), add_context_filter() 추가: add_action/add_filter 콜백 함수로 protected 메소드 호출 가능
  * ajax_helper(), short_code_helper() 추가: control_helpe공r()의 래퍼
* axis lock 기능 추가.
  * 의존성 있는 플러그인에서 axis의 deactivation을 막을 수 있는 함수: dispatch:lock_axis_framework()
* utils\admin_menu_notification_bubble() 함수 추가
  
2015년 06월 20일
* Routing Context 도입.
  * 생성인자를 통해 손쉽게 플러그인이 원하는 URL 생성 후, 원하는 컨트롤러에 URL 매핑 가능

# Ver 0.20.0000Alpha1
2015년 05월 29일
* 액시스의 bootstrap, callback object 구조는 폐기하기로 구상.
* 대안으로 dispatch, context 구조를 도입 구상.
* namespace 구조를 변경. 'includes' 부분을 삭제.
* 실제 패스 상에서도 includes 부분을 삭제.
* 콘텍스트 인터페이스 혹은 트레잇 구상.
* master branch로 편입. 

# Ver. 0.10.2500
2015년 05월 01일

* 액시스 버전 함수 axis_version() 함수.
* 콘트롤러, 모델을 디렉토리별로도 관리할 수 있도록 기능 추가.

# Ver. 0.10.2000
2015년 04월 27일

- ORM 도입.
- loader 코드 로직 변경.
- bootstrap 코드에서 별도의 discover 콜백 함수 등록. 이로써 몇 개의 callback object 를 추가적으로 등록하기 위해 bootstrap 객체를 상속할 필요가 없어짐.

# Ver. 0.10.1000
2015년 04월 26일

- 최초로 버전 도입.
- axis 에서 axis-framework 로 이름을 변경하고 플러그인화.
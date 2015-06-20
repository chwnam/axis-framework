# Ver 0.20.1000
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
  * 의존성 있는 플러그인에서 axis의 deactivation을 막을 수 있는 함수
  
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
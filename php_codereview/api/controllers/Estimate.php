<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author :	박현진
| Create-Date : 2018-03-05
| Memo : 견적의뢰 관리 API
|------------------------------------------------------------------------
*/

class Estimate extends MY_Controller {

  function __construct(){
    parent::__construct();

		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('global_function');

    $this->load->model('estimate/model_estimate');
  }

  #제조사 조회
  #제조사 코드 값 - 국산 : 0 , 수입 : 1
	public function maker_list(){

		header('Content-Type: application/json');

		$response = new stdClass;

    $data['nation_type'] = 0;
    $domestic_maker_result_list = $this->model_estimate->maker_list($data); //국산 제조사 조회

    $data['nation_type'] = 1;
    $foreign_maker_result_list = $this->model_estimate->maker_list($data); //수입 제조사 조회

    $x = 0;
    $y = 0;

    $domestic_array = array();
    $foreign_array = array();

    foreach($domestic_maker_result_list as $row) {
      $domestic_array[$x]['nation_type'] = $row->nation_type;
      $domestic_array[$x]['car_maker_idx'] = $row->car_maker_idx;
      $domestic_array[$x]['maker_name'] = $row->maker_name;
      $domestic_array[$x]['img_path'] = $row->img_path;

      $x ++;
    }

    foreach($foreign_maker_result_list as $row) {
      $foreign_array[$y]['nation_type'] = $row->nation_type;
      $foreign_array[$y]['car_maker_idx'] = $row->car_maker_idx;
      $foreign_array[$y]['maker_name'] = $row->maker_name;
      $foreign_array[$y]['img_path'] = $row->img_path;

      $y ++;
    }

    $response->code = "1000";
    $response->code_msg = "정상";
    $response->domestic_array = $domestic_array;
    $response->foreign_array = $foreign_array;

    echo json_encode($response);
    exit;
  }

  #차량 조회
	public function model_list(){

		header('Content-Type: application/json');

    $car_maker_idx = ($this->input->post("car_maker_idx", TRUE) != "")	?	$this->escstr($this->input->post("car_maker_idx", TRUE)) : "";

		$response = new stdClass;

    $data['car_maker_idx'] = $car_maker_idx;

    $result_list = $this->model_estimate->model_list($data); //모델 리스트 조회

    $x = 0;
    $data_array = array();

    foreach($result_list as $row) {
      $data_array[$x]['maker_name'] = $row->maker_name;
      $data_array[$x]['car_model_idx'] = $row->car_model_idx;
      $data_array[$x]['model_name'] = $row->model_name;
      $data_array[$x]['img_path'] = $row->img_path;

      $data['car_model_idx'] = $row->car_model_idx;
      $price_range = $this->model_estimate->model_price_range($data); //모델시리즈 가격범위

      if(empty($price_range->price_max)) {
        $data_array[$x]['price_range'] = "0 ~ 0 만원";
      } else {
        $data_array[$x]['price_range'] = number_format($price_range->price_min/10000)." ~ ".number_format($price_range->price_max/10000)." 만원";
      }
      $x++;
    }

    if($x == 0) {
      $response->code = "-1";
      $response->code_msg = "조회된 리스트가 없습니다.";
      $response->list_cnt = $x;
      $response->data_array = $data_array;

    }else {
      $response->code = "1000";
      $response->code_msg = "정상";
      $response->list_cnt = $x;
      $response->data_array = $data_array;
    }

    echo json_encode($response);
    exit;
  }

  #세부모델 옵션정보 리스트
  public function detail_model_list(){

    header('Content-Type: application/json');

    $response = new stdClass;

    $car_model_idx = ($this->input->post("car_model_idx", TRUE) != "")	?	$this->escstr($this->input->post("car_model_idx", TRUE)) : "1";

    $data['car_model_idx'] = $car_model_idx;

    $result_list = $this->model_estimate->detail_model_list($data); //차량정보 조회

    $x = 0;
    $data_array = array();

    $model_name = "";
    $img_path = "";

    foreach($result_list as $row) {
      $data_array2 = array();

      $model_name = $row->model_name;
      $img_path = $row->img_path;

      $data_array[$x]['car_name'] = $row->car_name;

      $car_product_idx = explode(",",$row->car_product_idx);
      $car_product_option = explode(",",$row->car_product_option);
      $car_product_price = explode(",",$row->car_product_price);

      for($i = 0; $i < count($car_product_idx); $i++) {
        $data_array2[$i]['car_product_idx'] = $car_product_idx[$i];
        $data_array2[$i]['car_product_option'] = $car_product_option[$i];
        $data_array2[$i]['car_product_price'] = number_format($car_product_price[$i]/10000)." 만원";
      }

      $data_array[$x]['car_detail_info'] = $data_array2;

      $x++;
    }

    if($x == 0) {
      $response->code = "-1";
      $response->code_msg = "조회된 리스트가 없습니다.";
      $response->list_cnt = $x;
      $response->data_array = $data_array;

    }else {
      $response->code = "1000";
      $response->code_msg = "정상";
      $response->list_cnt = $x;
      $response->model_name = $model_name;
      $response->img_path = $img_path;
      $response->data_array = $data_array;
    }

    echo json_encode($response);
    exit;
  }

  #세부모델별 셋팅 정보 조회
  public function estimate_setting_info(){

    header('Content-Type: application/json');

    $response = new stdClass;

    $car_product_idx = ($this->input->post("car_product_idx", TRUE) != "")	?	$this->escstr($this->input->post("car_product_idx", TRUE)) : "";

    $data['car_product_idx'] = $car_product_idx;

    $car_product_info = $this->model_estimate->car_product_info($data); //차량 정보 조회

    $data['color_type'] = "O";
    $outer_color_list = $this->model_estimate->product_color_list($data); //차량 외관 색상 정보 조회

    $data['color_type'] = "I";
    $inner_color_list = $this->model_estimate->product_color_list($data); //차량 내장 색상 정보 조회

    $city_list = $this->model_estimate->city_list(); //도시 리스트 조회

    $city_cd = $city_list[0]->city_cd;

    $data['city_cd'] = $city_cd;
    $region_list = $this->model_estimate->region_list($data); //지역 리스트 조회

    $data['type'] = "0"; //추가할인
    $add_discount_list = $this->model_estimate->category_management_list($data); //추가할인 리스트 조회

    $data['type'] = "1"; //출고서비스
    $factory_service_list = $this->model_estimate->category_management_list($data); //출고서비스 리스트 조회

    $data['type'] = "2"; //금융사
    $financial_list = $this->model_estimate->category_management_list($data); //금융사 리스트 조회

    $add_option_list = $this->model_estimate->add_option_list(); //추가 옵션 정보

    $this->model_estimate->car_product_viws_up_in($data); //조회수 1올림

    $outer_color_array = array(); //외장 색상 정보
    $inner_color_array = array(); //내장 색상 정보
    $city_array = array(); //도시 정보
    $region_array = array(); //지역 정보
    $add_discount_array = array(); //추가할인 정보
    $factory_service_array = array(); //출고 서비스 정보
    $financial_array = array(); //금융사 정보
    $add_option_array = array(); //추가옵션 정보 조회

    $x = 0;
    foreach($outer_color_list as $row) {
      $outer_color_array[$x]['car_product_color_idx'] = $row->car_product_color_idx;
      $outer_color_array[$x]['color_type'] = $row->color_type;
      $outer_color_array[$x]['color_name'] = $row->color_name;
      $outer_color_array[$x]['color_price'] = $row->color_price;
      $outer_color_array[$x]['img_path'] = $row->img_path;

      $x++;
    }

    $x = 0;
    foreach($inner_color_list as $row) {
      $inner_color_array[$x]['car_product_color_idx'] = $row->car_product_color_idx;
      $inner_color_array[$x]['color_type'] = $row->color_type;
      $inner_color_array[$x]['color_name'] = $row->color_name;
      $inner_color_array[$x]['color_price'] = $row->color_price;
      $inner_color_array[$x]['img_path'] = $row->img_path;

      $x++;
    }

    $x = 0;
    foreach($city_list as $row) {
      $city_array[$x]['city_cd_idx'] = $row->city_cd_idx;
      $city_array[$x]['city_cd'] = $row->city_cd;
      $city_array[$x]['city_name'] = $row->city_name;

      $x++;
    }

    $x = 0;
    foreach($region_list as $row) {
      $region_array[$x]['region_cd_idx'] = $row->region_cd_idx;
      $region_array[$x]['region_name'] = $row->region_name;

      $x++;
    }

    $x = 0;
    foreach($add_discount_list as $row) {
      $add_discount_array[$x]['add_discount_idx'] = $row->category_management_idx;
      $add_discount_array[$x]['add_discount_name'] = $row->category_name;

      $x++;
    }

    $x = 0;
    foreach($factory_service_list as $row) {
      $factory_service_array[$x]['factory_service_idx'] = $row->category_management_idx;
      $factory_service_array[$x]['factory_service_name'] = $row->category_name;

      $x++;
    }

    $x = 0;
    foreach($financial_list as $row) {
      $financial_array[$x]['financial_idx'] = $row->category_management_idx;
      $financial_array[$x]['financial_name'] = $row->category_name;

      $x++;
    }

    $x = 0;
    foreach($add_option_list as $row) {
      $add_option_array[$x]['car_product_option_idx'] = $row->car_product_option_idx;
      $add_option_array[$x]['option_name'] = $row->option_name;
      $add_option_array[$x]['option_price'] = $row->option_price;
      $add_option_array[$x]['contents'] = $row->contents;

      $x++;
    }

    $response->code = "1000";
    $response->code_msg = "정상";

    $response->car_product_idx = $car_product_info->car_product_idx;
    $response->car_product_option = $car_product_info->car_product_option;
    $response->car_name = $car_product_info->car_name;
    $response->img_path = $car_product_info->img_path;
    $response->car_product_price = $car_product_info->car_product_price;
    $response->car_price = $car_product_info->car_price;
    $response->model_name = $car_product_info->model_name;
    $response->maker_name = $car_product_info->maker_name;
    $response->point_yn = $car_product_info->point_yn;
    $response->nation_type = $car_product_info->nation_type;

    $response->outer_color_array = $outer_color_array;
    $response->inner_color_array = $inner_color_array;
    $response->city_array = $city_array;
    $response->region_array = $region_array;
    $response->add_discount_array = $add_discount_array;
    $response->factory_service_array = $factory_service_array;
    $response->financial_array = $financial_array;
    $response->add_option_array = $add_option_array;

    echo json_encode($response);
    exit;
  }

  #구매지역 정보조회
  public function address_region_info(){

    header('Content-Type: application/json');

    $response = new stdClass;

    $city_cd = ($this->input->post("city_cd", TRUE) != "")	?	$this->escstr($this->input->post("city_cd", TRUE)) : "1";

    $data['city_cd'] = $city_cd;
    $region_list = $this->model_estimate->region_list($data); //지역 리스트 조회

    $region_array = array(); //지역 정보

    $x = 0;
    foreach($region_list as $row) {
      $region_array[$x]['region_cd_idx'] = $row->region_cd_idx;
      $region_array[$x]['region_name'] = $row->region_name;

      $x++;
    }

    $response->code = "1000";
    $response->code_msg = "정상";
    $response->region_array = $region_array;

    echo json_encode($response);
    exit;
  }

  #견적 정보 저장
  #견적 저장 시 딜러들에게 견적이 올라왔음을 알람으로 보내야함 - $this->_alarm_action
  public function estimate_reg(){

    header('Content-Type: application/json');

    $response = new stdClass;

    $member_idx=($this->input->post("member_idx", true) != "") ? $this->escstr($this->input->post("member_idx", true)) : "";
    $nation_type=($this->input->post("nation_type", true) != "") ? $this->escstr($this->input->post("nation_type", true)) : "";
    $car_product_idx=($this->input->post("car_product_idx", true) != "") ? $this->escstr($this->input->post("car_product_idx", true)) : "";
    $outer_color_idx=($this->input->post("outer_color_idx", true) != "") ? $this->escstr($this->input->post("outer_color_idx", true)) : "";
    $outer_color=($this->input->post("outer_color", true) != "") ? $this->escstr($this->input->post("outer_color", true)) : "";
    $outer_color_price=($this->input->post("outer_color_price", true) != "") ? $this->escstr($this->input->post("outer_color_price", true)) : "";
    $inner_color_idx=($this->input->post("inner_color_idx", true) != "") ? $this->escstr($this->input->post("inner_color_idx", true)) : "";
    $inner_color=($this->input->post("inner_color", true) != "") ? $this->escstr($this->input->post("inner_color", true)) : "";
    $inner_color_price=($this->input->post("inner_color_price", true) != "") ? $this->escstr($this->input->post("inner_color_price", true)) : "";
    $car_product_option_idx=($this->input->post("car_product_option_idx", true) != "") ? $this->escstr($this->input->post("car_product_option_idx", true)) : "";
    $car_product_option_name=($this->input->post("car_product_option_name", true) != "") ? $this->escstr($this->input->post("car_product_option_name", true)) : "";
    $car_product_option_price=($this->input->post("car_product_option_price", true) != "") ? $this->escstr($this->input->post("car_product_option_price", true)) : "";
    $hyundai_point_yn=($this->input->post("hyundai_point_yn", true) != "") ? $this->escstr($this->input->post("hyundai_point_yn", true)) : "";
    $first_area_idx=($this->input->post("first_area_idx", true) != "") ? $this->escstr($this->input->post("first_area_idx", true)) : "";
    $first_area_name=($this->input->post("first_area_name", true) != "") ? $this->escstr($this->input->post("first_area_name", true)) : "";
    $second_area_idx=($this->input->post("second_area_idx", true) != "") ? $this->escstr($this->input->post("second_area_idx", true)) : "";
    $second_area_name=($this->input->post("second_area_name", true) != "") ? $this->escstr($this->input->post("second_area_name", true)) : "";
    $add_reduce_idx=($this->input->post("add_reduce_idx", true) != "") ? $this->escstr($this->input->post("add_reduce_idx", true)) : "";
    $add_reduce_name=($this->input->post("add_reduce_name", true) != "") ? $this->escstr($this->input->post("add_reduce_name", true)) : "";
    $release_service_idx=($this->input->post("release_service_idx", true) != "") ? $this->escstr($this->input->post("release_service_idx", true)) : "";
    $release_service_name=($this->input->post("release_service_name", true) != "") ? $this->escstr($this->input->post("release_service_name", true)) : "";
    $reduce_request_price=($this->input->post("reduce_request_price", true) != "") ? $this->escstr($this->input->post("reduce_request_price", true)) : "";
    $end_date=($this->input->post("end_date", true) != "") ? $this->escstr($this->input->post("end_date", true)) : "";
    $buy_person_type=($this->input->post("buy_person_type", true) != "") ? $this->escstr($this->input->post("buy_person_type", true)) : "";
    $buy_submit_type=($this->input->post("buy_submit_type", true) != "") ? $this->escstr($this->input->post("buy_submit_type", true)) : "";
    $buy_period_type=($this->input->post("buy_period_type", true) != "") ? $this->escstr($this->input->post("buy_period_type", true)) : "";
    $buy_bond_type=($this->input->post("buy_bond_type", true) != "") ? $this->escstr($this->input->post("buy_bond_type", true)) : "";
    $buy_prepayment_rate=($this->input->post("buy_prepayment_rate", true) != "") ? $this->escstr($this->input->post("buy_prepayment_rate", true)) : "";
    $buy_prepayment_price=($this->input->post("buy_prepayment_price", true) != "") ? $this->escstr($this->input->post("buy_prepayment_price", true)) : "";
    $buy_deposit_rate=($this->input->post("buy_deposit_rate", true) != "") ? $this->escstr($this->input->post("buy_deposit_rate", true)) : "";
    $buy_deposit_price=($this->input->post("buy_deposit_price", true) != "") ? $this->escstr($this->input->post("buy_deposit_price", true)) : "";
    $buy_bank_idx=($this->input->post("buy_bank_idx", true) != "") ? $this->escstr($this->input->post("buy_bank_idx", true)) : "";
    $buy_bank_name=($this->input->post("buy_bank_name", true) != "") ? $this->escstr($this->input->post("buy_bank_name", true)) : "";
    $buy_remains_rate=($this->input->post("buy_remains_rate", true) != "") ? $this->escstr($this->input->post("buy_remains_rate", true)) : "";
    $buy_remains_price=($this->input->post("buy_remains_price", true) != "") ? $this->escstr($this->input->post("buy_remains_price", true)) : "";
    $buy_mycar_type=($this->input->post("buy_mycar_type", true) != "") ? $this->escstr($this->input->post("buy_mycar_type", true)) : "";
    $buy_mycar_name=($this->input->post("buy_mycar_name", true) != "") ? $this->escstr($this->input->post("buy_mycar_name", true)) : "";
    $buy_mycar_number=($this->input->post("buy_mycar_number", true) != "") ? $this->escstr($this->input->post("buy_mycar_number", true)) : "";
    $buy_mycar_body_number=($this->input->post("buy_mycar_body_number", true) != "") ? $this->escstr($this->input->post("buy_mycar_body_number", true)) : "";
    $buy_mycar_moving_distance=($this->input->post("buy_mycar_moving_distance", true) != "") ? $this->escstr($this->input->post("buy_mycar_moving_distance", true)) : "";
    $buy_mycar_mission=($this->input->post("buy_mycar_mission", true) != "") ? $this->escstr($this->input->post("buy_mycar_mission", true)) : "";
    $buy_mycar_option=($this->input->post("buy_mycar_option", true) != "") ? $this->escstr($this->input->post("buy_mycar_option", true)) : "";
    $buy_mycar_color=($this->input->post("buy_mycar_color", true) != "") ? $this->escstr($this->input->post("buy_mycar_color", true)) : "";
    $buy_mycar_selling_area=($this->input->post("buy_mycar_selling_area", true) != "") ? $this->escstr($this->input->post("buy_mycar_selling_area", true)) : "";
    $buy_mycar_buy_type=($this->input->post("buy_mycar_buy_type", true) != "") ? $this->escstr($this->input->post("buy_mycar_buy_type", true)) : "";
    $buy_mycar_accident=($this->input->post("buy_mycar_accident", true) != "") ? $this->escstr($this->input->post("buy_mycar_accident", true)) : "";
    $buy_mycar_img_1=($this->input->post("buy_mycar_img_1", true) != "") ? $this->escstr($this->input->post("buy_mycar_img_1", true)) : "";
    $buy_mycar_img_2=($this->input->post("buy_mycar_img_2", true) != "") ? $this->escstr($this->input->post("buy_mycar_img_2", true)) : "";
    $buy_mycar_img_3=($this->input->post("buy_mycar_img_3", true) != "") ? $this->escstr($this->input->post("buy_mycar_img_3", true)) : "";
    $buy_mycar_img_4=($this->input->post("buy_mycar_img_4", true) != "") ? $this->escstr($this->input->post("buy_mycar_img_4", true)) : "";
    $buy_mycar_img_5=($this->input->post("buy_mycar_img_5", true) != "") ? $this->escstr($this->input->post("buy_mycar_img_5", true)) : "";
    $buy_mycar_img_6=($this->input->post("buy_mycar_img_6", true) != "") ? $this->escstr($this->input->post("buy_mycar_img_6", true)) : "";
    $estimated_amount=($this->input->post("estimated_amount", true) != "") ? $this->escstr($this->input->post("estimated_amount", true)) : "";

    $data['member_idx']=$member_idx;
    $data['nation_type']=$nation_type;
    $data['car_product_idx']=$car_product_idx;
    $data['outer_color_idx']=$outer_color_idx;
    $data['outer_color']=$outer_color;
    $data['outer_color_price']=$outer_color_price;
    $data['inner_color_idx']=$inner_color_idx;
    $data['inner_color']=$inner_color;
    $data['inner_color_price']=$inner_color_price;
    $data['car_product_option_idx']=$car_product_option_idx;
    $data['car_product_option_name']=$car_product_option_name;
    $data['car_product_option_price']=$car_product_option_price;
    $data['hyundai_point_yn']=$hyundai_point_yn;
    $data['first_area_idx']=$first_area_idx;
    $data['first_area_name']=$first_area_name;
    $data['second_area_idx']=$second_area_idx;
    $data['second_area_name']=$second_area_name;
    $data['add_reduce_idx']=$add_reduce_idx;
    $data['add_reduce_name']=$add_reduce_name;
    $data['release_service_idx']=$release_service_idx;
    $data['release_service_name']=$release_service_name;
    $data['reduce_request_price']=$reduce_request_price;
    $data['end_date']=$end_date;
    $data['buy_person_type']=$buy_person_type;
    $data['buy_submit_type']=$buy_submit_type;
    $data['buy_period_type']=$buy_period_type;
    $data['buy_bond_type']=$buy_bond_type;
    $data['buy_prepayment_rate']=$buy_prepayment_rate;
    $data['buy_prepayment_price']=$buy_prepayment_price;
    $data['buy_deposit_rate']=$buy_deposit_rate;
    $data['buy_deposit_price']=$buy_deposit_price;
    $data['buy_bank_idx']=$buy_bank_idx;
    $data['buy_bank_name']=$buy_bank_name;
    $data['buy_remains_rate']=$buy_remains_rate;
    $data['buy_remains_price']=$buy_remains_price;
    $data['buy_mycar_type']=$buy_mycar_type;
    $data['buy_mycar_name']=$buy_mycar_name;
    $data['buy_mycar_number']=$buy_mycar_number;
    $data['buy_mycar_body_number']=$buy_mycar_body_number;
    $data['buy_mycar_moving_distance']=$buy_mycar_moving_distance;
    $data['buy_mycar_mission']=$buy_mycar_mission;
    $data['buy_mycar_option']=$buy_mycar_option;
    $data['buy_mycar_color']=$buy_mycar_color;
    $data['buy_mycar_selling_area']=$buy_mycar_selling_area;
    $data['buy_mycar_buy_type']=$buy_mycar_buy_type;
    $data['buy_mycar_accident']=$buy_mycar_accident;
    $data['buy_mycar_img_1']=$buy_mycar_img_1;
    $data['buy_mycar_img_2']=$buy_mycar_img_2;
    $data['buy_mycar_img_3']=$buy_mycar_img_3;
    $data['buy_mycar_img_4']=$buy_mycar_img_4;
    $data['buy_mycar_img_5']=$buy_mycar_img_5;
    $data['buy_mycar_img_6']=$buy_mycar_img_6;
    $data['estimated_amount']=$estimated_amount;

    $result = $this->model_estimate->estimate_reg_in($data); //경매정보 저장

    if($result == "1") {
      $alarm_data["member_idx"] = $member_idx;

      $this->_alarm_action($alarm_data["member_idx"],0,$alarm_data); //경매알람 발송
    }

    $response = new stdClass;

    if($result === "1"){
      $response->code = "1000";
      $response->code_msg = $this->global_msg->code_msg('1000');

      echo json_encode($response);
      exit;

    }else{
      $response->code = "-1";
      $response->code_msg = $this->global_msg->code_msg('-1');

      echo json_encode($response);
      exit;

    }
  }

  #경매저장
  public function estimate_up(){

    header('Content-Type: application/json');

    $response = new stdClass;

    $estimate_idx=($this->input->post("estimate_idx", true) != "") ? $this->escstr($this->input->post("estimate_idx", true)) : "";
    $member_idx=($this->input->post("member_idx", true) != "") ? $this->escstr($this->input->post("member_idx", true)) : "";
    $nation_type=($this->input->post("nation_type", true) != "") ? $this->escstr($this->input->post("nation_type", true)) : "";
    $car_product_idx=($this->input->post("car_product_idx", true) != "") ? $this->escstr($this->input->post("car_product_idx", true)) : "";
    $outer_color_idx=($this->input->post("outer_color_idx", true) != "") ? $this->escstr($this->input->post("outer_color_idx", true)) : "";
    $outer_color=($this->input->post("outer_color", true) != "") ? $this->escstr($this->input->post("outer_color", true)) : "";
    $outer_color_price=($this->input->post("outer_color_price", true) != "") ? $this->escstr($this->input->post("outer_color_price", true)) : "";
    $inner_color_idx=($this->input->post("inner_color_idx", true) != "") ? $this->escstr($this->input->post("inner_color_idx", true)) : "";
    $inner_color=($this->input->post("inner_color", true) != "") ? $this->escstr($this->input->post("inner_color", true)) : "";
    $inner_color_price=($this->input->post("inner_color_price", true) != "") ? $this->escstr($this->input->post("inner_color_price", true)) : "";
    $car_product_option_idx=($this->input->post("car_product_option_idx", true) != "") ? $this->escstr($this->input->post("car_product_option_idx", true)) : "";
    $car_product_option_name=($this->input->post("car_product_option_name", true) != "") ? $this->escstr($this->input->post("car_product_option_name", true)) : "";
    $car_product_option_price=($this->input->post("car_product_option_price", true) != "") ? $this->escstr($this->input->post("car_product_option_price", true)) : "";
    $hyundai_point_yn=($this->input->post("hyundai_point_yn", true) != "") ? $this->escstr($this->input->post("hyundai_point_yn", true)) : "";
    $first_area_idx=($this->input->post("first_area_idx", true) != "") ? $this->escstr($this->input->post("first_area_idx", true)) : "";
    $first_area_name=($this->input->post("first_area_name", true) != "") ? $this->escstr($this->input->post("first_area_name", true)) : "";
    $second_area_idx=($this->input->post("second_area_idx", true) != "") ? $this->escstr($this->input->post("second_area_idx", true)) : "";
    $second_area_name=($this->input->post("second_area_name", true) != "") ? $this->escstr($this->input->post("second_area_name", true)) : "";
    $add_reduce_idx=($this->input->post("add_reduce_idx", true) != "") ? $this->escstr($this->input->post("add_reduce_idx", true)) : "";
    $add_reduce_name=($this->input->post("add_reduce_name", true) != "") ? $this->escstr($this->input->post("add_reduce_name", true)) : "";
    $release_service_idx=($this->input->post("release_service_idx", true) != "") ? $this->escstr($this->input->post("release_service_idx", true)) : "";
    $release_service_name=($this->input->post("release_service_name", true) != "") ? $this->escstr($this->input->post("release_service_name", true)) : "";
    $reduce_request_price=($this->input->post("reduce_request_price", true) != "") ? $this->escstr($this->input->post("reduce_request_price", true)) : "";
    $end_date=($this->input->post("end_date", true) != "") ? $this->escstr($this->input->post("end_date", true)) : "";
    $buy_person_type=($this->input->post("buy_person_type", true) != "") ? $this->escstr($this->input->post("buy_person_type", true)) : "";
    $buy_submit_type=($this->input->post("buy_submit_type", true) != "") ? $this->escstr($this->input->post("buy_submit_type", true)) : "";
    $buy_period_type=($this->input->post("buy_period_type", true) != "") ? $this->escstr($this->input->post("buy_period_type", true)) : "";
    $buy_bond_type=($this->input->post("buy_bond_type", true) != "") ? $this->escstr($this->input->post("buy_bond_type", true)) : "";
    $buy_prepayment_rate=($this->input->post("buy_prepayment_rate", true) != "") ? $this->escstr($this->input->post("buy_prepayment_rate", true)) : "";
    $buy_prepayment_price=($this->input->post("buy_prepayment_price", true) != "") ? $this->escstr($this->input->post("buy_prepayment_price", true)) : "";
    $buy_deposit_rate=($this->input->post("buy_deposit_rate", true) != "") ? $this->escstr($this->input->post("buy_deposit_rate", true)) : "";
    $buy_deposit_price=($this->input->post("buy_deposit_price", true) != "") ? $this->escstr($this->input->post("buy_deposit_price", true)) : "";
    $buy_bank_idx=($this->input->post("buy_bank_idx", true) != "") ? $this->escstr($this->input->post("buy_bank_idx", true)) : "";
    $buy_bank_name=($this->input->post("buy_bank_name", true) != "") ? $this->escstr($this->input->post("buy_bank_name", true)) : "";
    $buy_remains_rate=($this->input->post("buy_remains_rate", true) != "") ? $this->escstr($this->input->post("buy_remains_rate", true)) : "";
    $buy_remains_price=($this->input->post("buy_remains_price", true) != "") ? $this->escstr($this->input->post("buy_remains_price", true)) : "";
    $buy_mycar_type=($this->input->post("buy_mycar_type", true) != "") ? $this->escstr($this->input->post("buy_mycar_type", true)) : "";
    $buy_mycar_name=($this->input->post("buy_mycar_name", true) != "") ? $this->escstr($this->input->post("buy_mycar_name", true)) : "";
    $buy_mycar_number=($this->input->post("buy_mycar_number", true) != "") ? $this->escstr($this->input->post("buy_mycar_number", true)) : "";
    $buy_mycar_body_number=($this->input->post("buy_mycar_body_number", true) != "") ? $this->escstr($this->input->post("buy_mycar_body_number", true)) : "";
    $buy_mycar_moving_distance=($this->input->post("buy_mycar_moving_distance", true) != "") ? $this->escstr($this->input->post("buy_mycar_moving_distance", true)) : "";
    $buy_mycar_mission=($this->input->post("buy_mycar_mission", true) != "") ? $this->escstr($this->input->post("buy_mycar_mission", true)) : "";
    $buy_mycar_option=($this->input->post("buy_mycar_option", true) != "") ? $this->escstr($this->input->post("buy_mycar_option", true)) : "";
    $buy_mycar_color=($this->input->post("buy_mycar_color", true) != "") ? $this->escstr($this->input->post("buy_mycar_color", true)) : "";
    $buy_mycar_selling_area=($this->input->post("buy_mycar_selling_area", true) != "") ? $this->escstr($this->input->post("buy_mycar_selling_area", true)) : "";
    $buy_mycar_buy_type=($this->input->post("buy_mycar_buy_type", true) != "") ? $this->escstr($this->input->post("buy_mycar_buy_type", true)) : "";
    $buy_mycar_accident=($this->input->post("buy_mycar_accident", true) != "") ? $this->escstr($this->input->post("buy_mycar_accident", true)) : "";
    $buy_mycar_img_1=($this->input->post("buy_mycar_img_1", true) != "") ? $this->escstr($this->input->post("buy_mycar_img_1", true)) : "";
    $buy_mycar_img_2=($this->input->post("buy_mycar_img_2", true) != "") ? $this->escstr($this->input->post("buy_mycar_img_2", true)) : "";
    $buy_mycar_img_3=($this->input->post("buy_mycar_img_3", true) != "") ? $this->escstr($this->input->post("buy_mycar_img_3", true)) : "";
    $buy_mycar_img_4=($this->input->post("buy_mycar_img_4", true) != "") ? $this->escstr($this->input->post("buy_mycar_img_4", true)) : "";
    $buy_mycar_img_5=($this->input->post("buy_mycar_img_5", true) != "") ? $this->escstr($this->input->post("buy_mycar_img_5", true)) : "";
    $buy_mycar_img_6=($this->input->post("buy_mycar_img_6", true) != "") ? $this->escstr($this->input->post("buy_mycar_img_6", true)) : "";
    $estimated_amount=($this->input->post("estimated_amount", true) != "") ? $this->escstr($this->input->post("estimated_amount", true)) : "";

    $data['estimate_idx']=$estimate_idx;
    $data['member_idx']=$member_idx;
    $data['nation_type']=$nation_type;
    $data['car_product_idx']=$car_product_idx;
    $data['outer_color_idx']=$outer_color_idx;
    $data['outer_color']=$outer_color;
    $data['outer_color_price']=$outer_color_price;
    $data['inner_color_idx']=$inner_color_idx;
    $data['inner_color']=$inner_color;
    $data['inner_color_price']=$inner_color_price;
    $data['car_product_option_idx']=$car_product_option_idx;
    $data['car_product_option_name']=$car_product_option_name;
    $data['car_product_option_price']=$car_product_option_price;
    $data['hyundai_point_yn']=$hyundai_point_yn;
    $data['first_area_idx']=$first_area_idx;
    $data['first_area_name']=$first_area_name;
    $data['second_area_idx']=$second_area_idx;
    $data['second_area_name']=$second_area_name;
    $data['add_reduce_idx']=$add_reduce_idx;
    $data['add_reduce_name']=$add_reduce_name;
    $data['release_service_idx']=$release_service_idx;
    $data['release_service_name']=$release_service_name;
    $data['reduce_request_price']=$reduce_request_price;
    $data['end_date']=$end_date;
    $data['buy_person_type']=$buy_person_type;
    $data['buy_submit_type']=$buy_submit_type;
    $data['buy_period_type']=$buy_period_type;
    $data['buy_bond_type']=$buy_bond_type;
    $data['buy_prepayment_rate']=$buy_prepayment_rate;
    $data['buy_prepayment_price']=$buy_prepayment_price;
    $data['buy_deposit_rate']=$buy_deposit_rate;
    $data['buy_deposit_price']=$buy_deposit_price;
    $data['buy_bank_idx']=$buy_bank_idx;
    $data['buy_bank_name']=$buy_bank_name;
    $data['buy_remains_rate']=$buy_remains_rate;
    $data['buy_remains_price']=$buy_remains_price;
    $data['buy_mycar_type']=$buy_mycar_type;
    $data['buy_mycar_name']=$buy_mycar_name;
    $data['buy_mycar_number']=$buy_mycar_number;
    $data['buy_mycar_body_number']=$buy_mycar_body_number;
    $data['buy_mycar_moving_distance']=$buy_mycar_moving_distance;
    $data['buy_mycar_mission']=$buy_mycar_mission;
    $data['buy_mycar_option']=$buy_mycar_option;
    $data['buy_mycar_color']=$buy_mycar_color;
    $data['buy_mycar_selling_area']=$buy_mycar_selling_area;
    $data['buy_mycar_buy_type']=$buy_mycar_buy_type;
    $data['buy_mycar_accident']=$buy_mycar_accident;
    $data['buy_mycar_img_1']=$buy_mycar_img_1;
    $data['buy_mycar_img_2']=$buy_mycar_img_2;
    $data['buy_mycar_img_3']=$buy_mycar_img_3;
    $data['buy_mycar_img_4']=$buy_mycar_img_4;
    $data['buy_mycar_img_5']=$buy_mycar_img_5;
    $data['buy_mycar_img_6']=$buy_mycar_img_6;
    $data['estimated_amount']=$estimated_amount;

    if($outer_color_idx == "") {
      $response->code = "-1";
      $response->code_msg = "외관 색상 키값은 필수입니다.";

      echo json_encode($response);
      exit;
    }

    $result = $this->model_estimate->estimate_up_in($data); //경매정보 저장

    if($result == "1") {
      $alarm_data["member_idx"] = $member_idx;

      $this->_alarm_action($alarm_data["member_idx"],3,$alarm_data); // 경매알람 발송
    }

    $response = new stdClass;

    if($result === "1"){
      $response->code = "1000";
      $response->code_msg = $this->global_msg->code_msg('1000');

      echo json_encode($response);
      exit;

    }else{
      $response->code = "-1";
      $response->code_msg = $this->global_msg->code_msg('-1');

      echo json_encode($response);
      exit;

    }
  }

  #경매 신청 내역
	public function estimate_request_history(){

		header('Content-Type: application/json');
    $page_num=($this->input->post("page_num", TRUE) != "")	?	$this->escstr($this->input->post("page_num", TRUE)) : "1";
    $member_idx = ($this->input->post("member_idx", TRUE) != "")	?	$this->escstr($this->input->post("member_idx", TRUE)) : "";

    $page_size=PAGESIZE;

    $data['page_size']=$page_size;
		$data['page_num']=($page_num-1)*$page_size;

		$response = new stdClass;

    $data['member_idx'] = $member_idx;

    $result_list = $this->model_estimate->estimate_request_history($data); //내 견전 리스트
    $result_list_count = $this->model_estimate->estimate_request_history_count($data); //내 견적 리스트 카운트

    $total_page=ceil($result_list_count/$page_size);

    $x = 0;
    $data_array = array();

    foreach($result_list as $row) {
      $data_array[$x]['estimate_idx'] = $row->estimate_idx;
      $data_array[$x]['first_area_idx'] = $row->first_area_idx;
      $data_array[$x]['first_area_name'] = $row->first_area_name;
      $data_array[$x]['buy_person_type'] = $row->buy_person_type;
      $data_array[$x]['buy_submit_type'] = $row->buy_submit_type;
      $data_array[$x]['buy_period_type'] = $row->buy_period_type;
      $data_array[$x]['model_name'] = $row->model_name;
      $data_array[$x]['maker_name'] = $row->maker_name;
      $data_array[$x]['car_name'] = $row->car_name;
      $data_array[$x]['car_product_option'] = $row->car_product_option;
      $data_array[$x]['views'] = $row->views;
      $data_array[$x]['state'] = $row->state;

      $end_date = strtotime($row->end_date);
      $today = strtotime(date("Ymd H:i:s"));
      $total_time = $end_date - $today;

      $years = floor($total_time/31536000);
      $days = floor($total_time/86400);
      $date = ($days -(365*$years))-1;
      $time = $total_time - ($days*86400);
      $hours = floor($time/3600);
      $time = $time - ($hours*3600);
      $min = floor($time/60);
      $sec = $time - ($min*60);

      // $diff = $end_date - $today;
      //
      // $hours = floor($diff/3600);
      // $diff = $diff - ($hours*3600);
      // $min = floor($diff/60);
      // $sec = $diff - ($min*60);
      //
      if($days < 0) {
				$date = "0";
				$hours = "00";
				$min = "00";
			}

      $data_array[$x]['end_date'] =	$date."일 ".$hours.":".$min; //남은 마감시간 계산

      $x++;
    }

    if($x == 0) {
      $response->code = "-1";
      $response->code_msg = "조회된 리스트가 없습니다.";
      $response->list_cnt = $x;
      $response->list_total_cnt = $result_list_count;
      $response->page_num = $page_num;
      $response->total_page =	$total_page;
      $response->data_array = $data_array;

    }else {
      $response->code = "1000";
      $response->code_msg = "정상";
      $response->list_cnt = $x;
      $response->list_total_cnt = $result_list_count;
      $response->page_num = $page_num;
      $response->total_page =	$total_page;
      $response->data_array = $data_array;
    }

    echo json_encode($response);
    exit;
  }

  #견적상세정보
  public function estimate_info(){

    header('Content-Type: application/json');

    $response = new stdClass;

    $estimate_idx = ($this->input->post("estimate_idx", TRUE) != "")	?	$this->escstr($this->input->post("estimate_idx", TRUE)) : "";

    $data['estimate_idx'] = $estimate_idx;

    $result = $this->model_estimate->estimate_info($data); //견적신청 상세 조회

    $dealer_select_count = $this->model_estimate->dealer_select_count($data); //선택 된 딜러 조회,이값으로 수정여부 판단

    if(empty($result)) {
      $response->code = "-2";
      $response->code_msg = $this->global_msg->code_msg('-2');

      echo json_encode($response);
      exit;
    }else {
      $response->code = "1000";
      $response->code_msg = "정상";

      $response->buy_person_type = $result->buy_person_type;
      $response->buy_person_name = $result->buy_person_name;
      $response->buy_submit_type = $result->buy_submit_type;
      $response->buy_submit_name = $result->buy_submit_name;
      $response->buy_period_type = $result->buy_period_type;

      $response->state = $result->state;

      $update_yn = "";

      //수정을 할 수 있는 경우는 요청, 경매마감이면서
      //구매확정이거나 선택이면 수정 못함
      if($result->state == "4" || $result->state == "5" || $dealer_select_count > 0) {
        $update_yn = "N";
      } else {
        $update_yn = "Y";
      }

      $response->update_yn = $update_yn;


      $response->ins_date = $result->ins_date;
      $response->reduce_request_price = $result->reduce_request_price;
      $response->estimated_amount = $result->estimated_amount;

      $response->first_area_idx = $result->first_area_idx;
      $response->first_area_name = $result->first_area_name;

      $response->second_area_idx = $result->second_area_idx;
      $response->second_area_name = $result->second_area_name;

      $response->release_service_idx = $result->release_service_idx;
      $response->release_service_name = $result->release_service_name;

      $response->outer_color_idx = $result->outer_color_idx;
      $response->inner_color_idx = $result->inner_color_idx;
      $response->outer_color = $result->outer_color;
      $response->inner_color = $result->inner_color;
      $response->outer_color_price = $result->outer_color_price;
      $response->inner_color_price = $result->inner_color_price;

      $response->add_reduce_idx = $result->add_reduce_idx;
      $response->add_reduce_name = $result->add_reduce_name;

  		$response->end_date = date('Y-m-d A h:i',strtotime($result->end_date));

  		$response->buy_period_type = $result->buy_period_type;
  		$response->buy_bond_type = $result->buy_bond_type;

  		$response->buy_prepayment_rate = $result->buy_prepayment_rate;
  		$response->buy_prepayment_price = $result->buy_prepayment_price;

  		$response->buy_deposit_rate = $result->buy_deposit_rate;
  		$response->buy_deposit_price = $result->buy_deposit_price;

      $response->buy_bank_idx = $result->buy_bank_idx;
      $response->buy_bank_name = $result->buy_bank_name;

  		$response->buy_remains_rate = $result->buy_remains_rate;
  		$response->buy_remains_price = $result->buy_remains_price;

      $response->car_product_option_idx = $result->car_product_option_idx;
      $response->car_product_option_name = $result->car_product_option_name;
      $response->car_product_option_price = $result->car_product_option_price;
      $response->car_product_option = $result->car_product_option;
      $response->car_product_idx = $result->car_product_idx;
      $response->car_name = $result->car_name;
      $response->car_price = $result->car_price;
      $response->model_name = $result->model_name;
      $response->maker_name = $result->maker_name;
      $response->img_path = $result->img_path;
      $response->buy_mycar_type = $result->buy_mycar_type;
      $response->buy_mycar_name = $result->buy_mycar_name;
      $response->buy_mycar_number = $result->buy_mycar_number;
      $response->buy_mycar_body_number = $result->buy_mycar_body_number;
      $response->buy_mycar_moving_distance = $result->buy_mycar_moving_distance;
      $response->buy_mycar_mission = $result->buy_mycar_mission;
      $response->buy_mycar_option = $result->buy_mycar_option;
      $response->buy_mycar_color = $result->buy_mycar_color;
      $response->buy_mycar_selling_area = $result->buy_mycar_selling_area;
      $response->buy_mycar_buy_type = $result->buy_mycar_buy_type;
      $response->buy_mycar_accident = $result->buy_mycar_accident;
      $response->buy_mycar_img_1 = $result->buy_mycar_img_1;
      $response->buy_mycar_img_2 = $result->buy_mycar_img_2;
      $response->buy_mycar_img_3 = $result->buy_mycar_img_3;
      $response->buy_mycar_img_4 = $result->buy_mycar_img_4;
      $response->buy_mycar_img_5 = $result->buy_mycar_img_5;
      $response->buy_mycar_img_6 = $result->buy_mycar_img_6;
      $response->nation_type = $result->nation_type;
      $response->point_yn = $result->point_yn;
      $response->hyundai_point_yn = $result->point_yn;

      echo json_encode($response);
      exit;
    }
  }

  #내 견적 참여딜러 리스트(견적 리스트 미포함)
	public function estimate_submit_dealer_list(){

		header('Content-Type: application/json');

    $estimate_idx = ($this->input->post("estimate_idx", TRUE) != "")	?	$this->escstr($this->input->post("estimate_idx", TRUE)) : "";
    $page_num = ($this->input->post("page_num", TRUE) != "")	?	$this->escstr($this->input->post("page_num", TRUE)) : "1";

    $page_size = PAGESIZE;
    $data['page_size'] = $page_size;
    $data['page_num'] = ($page_num-1)*$page_size;


		$response = new stdClass;

    $data['estimate_idx'] = $estimate_idx;
    $data['member_idx'] = "";

    $estimate_select_check = $this->model_estimate->estimate_select_check($data); //내 견적이 선택 및 구매확정인지 체크

    $dealer_list = "";
    $dealer_list_count = 0;
    $relieved_dealer_list = "";

    if(empty($estimate_select_check)) {
      $dealer_list = $this->model_estimate->estimate_submit_dealer_list($data); //내 견적 참여딜리 리스트 조회(일반딜러)
      $dealer_list_count = $this->model_estimate->estimate_submit_dealer_list_count($data); //내 견적 참여딜리 리스트 카운트(일반딜러)

      $relieved_dealer_list = $this->model_estimate->estimate_submit_relieved_dealer_list($data); //내 견적 참여딜러 리스트 조회(안심딜러)
    } else {
      if($estimate_select_check->relieved_dealer_type == "0") { //일반딜러

        $data['member_idx'] = $estimate_select_check->dealer_idx;

        $dealer_list = $this->model_estimate->estimate_submit_dealer_list($data); //내 견적 참여딜리 리스트 조회(일반딜러)
        $dealer_list_count = $this->model_estimate->estimate_submit_dealer_list_count($data); //내 견적 참여딜리 리스트 카운트(일반딜러)
      } else { //안심딜러
        $data['member_idx'] = $estimate_select_check->dealer_idx;
        $relieved_dealer_list = $this->model_estimate->estimate_submit_relieved_dealer_list($data); //내 견적 참여딜러 리스트 조회(안심딜러)
      }
    }

    $total_page=ceil($dealer_list_count/$page_size);

    $x = 0;
    $y = 0;
    $dealer_array = array(); //딜러리스트(일반딜러)
    $relieved_dealer_array = array(); //딜러리스트(안심딜러)

    //안심딜러
    $x = 0;

    if(!empty($relieved_dealer_list)) {
      foreach($relieved_dealer_list as $row) {
        $relieved_dealer_array[$x]['estimate_idx'] = $row->estimate_idx;
        $relieved_dealer_array[$x]['estimate_submit_idx'] = $row->estimate_submit_idx;
        $relieved_dealer_array[$x]['etc_reg_name'] = $row->etc_reg_name;
        $relieved_dealer_array[$x]['lease_rate'] = $row->lease_rate;
        $relieved_dealer_array[$x]['bank_name'] = $row->bank_name;
        $relieved_dealer_array[$x]['member_idx'] = $row->member_idx;

        $member_name = $row->member_name;

        if(!empty($member_name) ) {
          if($row->dealer_select_state != "1") {
            $len = mb_strlen($member_name);
            $member_name = mb_substr($member_name,0,1).str_repeat('*',$len-1); // P***
          }
        }

        $relieved_dealer_array[$x]['member_name'] = $member_name;
        $relieved_dealer_array[$x]['work_place'] = $row->work_place;
        $relieved_dealer_array[$x]['work_place_addr'] = $row->work_place_addr;
        $relieved_dealer_array[$x]['member_img_path'] = $row->member_img_path;
        $relieved_dealer_array[$x]['member_grade'] = $row->member_grade;
        $relieved_dealer_array[$x]['area_name'] = $row->area_name;
        $relieved_dealer_array[$x]['state'] = $row->state;

        $x++;
      }
    }

    //일반딜러
    $y = 0;

    if(!empty($dealer_list)) {

      foreach($dealer_list as $row) {
        $dealer_array[$y]['estimate_idx'] = $row->estimate_idx;
        $dealer_array[$y]['estimate_submit_idx'] = $row->estimate_submit_idx;
        $dealer_array[$y]['etc_reg_name'] = $row->etc_reg_name;
        $dealer_array[$y]['lease_rate'] = $row->lease_rate;
        $dealer_array[$y]['bank_name'] = $row->bank_name;
        $dealer_array[$y]['member_idx'] = $row->member_idx;

        $member_name = $row->member_name;

        if(!empty($member_name) ) {
          if($row->dealer_select_state != "1") {
            $len = mb_strlen($member_name);
            $member_name = mb_substr($member_name,0,1).str_repeat('*',$len-1); // P***
            $relieved_dealer_array[$x]['member_phone'] = $row->member_phone;
          }
        }

        $dealer_array[$y]['member_name'] = $member_name;
        $dealer_array[$y]['work_place'] = $row->work_place;
        $dealer_array[$y]['work_place_addr'] = $row->work_place_addr;
        $dealer_array[$y]['member_img_path'] = $row->member_img_path;
        $dealer_array[$y]['member_grade'] = $row->member_grade;
        $dealer_array[$y]['area_name'] = $row->area_name;
        $dealer_array[$y]['state'] = $row->state;

        $y++;
      }
    }

    if($x == 0 && $y == 0) {
      $response->code = "-2";
      $response->code_msg = $this->global_msg->code_msg('-2');
      $response->list_cnt = $x;
      $response->list_total_cnt = $dealer_list_count + count($relieved_dealer_list);
      $response->page_num = $page_num;
      $response->total_page =	$total_page;
      $response->dealer_array = $dealer_array;
      $response->relieved_dealer_array = $relieved_dealer_array;

    }else {
      $response->code = "1000";
      $response->code_msg = $this->global_msg->code_msg('1000');
      $response->list_cnt = $y;
      $response->list_total_cnt = $x + $y;
      $response->page_num = $page_num;
      $response->total_page =	$total_page;
      $response->dealer_array = $dealer_array;

      if(!empty($relieved_dealer_list)) {
        $response->relieved_dealer_array = $relieved_dealer_array;
      }
    }

    echo json_encode($response);
    exit;
  }

  #내 견적 참여딜러 리스트(견적 리스트 포함)
  public function estimate_list_dealer_list(){

    header('Content-Type: application/json');

    $member_idx = ($this->input->post("member_idx", TRUE) != "")	?	$this->escstr($this->input->post("member_idx", TRUE)) : "";
    $page_num = ($this->input->post("page_num", TRUE) != "")	?	$this->escstr($this->input->post("page_num", TRUE)) : "1";

    $page_size = PAGESIZE;
    $data['member_idx'] = $member_idx;
    $data['page_size'] = $page_size;
    $data['page_num'] = ($page_num-1)*$page_size;

    $response = new stdClass;

    $estimate_list = $this->model_estimate->estimate_request_history($data); //견적 신청 내역 리스트 조회

    $data['estimate_idx'] = $estimate_list[0]->estimate_idx; //최초견적키값 조회

    $dealer_list = $this->model_estimate->estimate_submit_dealer_list($data); //내 견적 참여딜리 리스트 조회(일반딜러)
    $dealer_list_count = $this->model_estimate->estimate_submit_dealer_list_count($data); //내 견적 참여딜리 리스트 카운트(일반딜러)
    $relieved_dealer_list = $this->model_estimate->estimate_submit_relieved_dealer_list($data); //내 견적 참여딜리 리스트 조회(안심딜러)

    $total_page=ceil($dealer_list_count/$page_size);

    $x = 0;
    $estimate_array = array(); //견적리스트
    $dealer_array = array(); //딜러리스트(일반딜러)
    $relieved_dealer_array = array(); //딜러리스트(안심딜러)

    foreach($estimate_list as $row) {
      $estimate_array[$x]['estimate_idx'] = $row->estimate_idx;
      $estimate_array[$x]['first_area_name'] = $row->first_area_name;
      $estimate_array[$x]['buy_person_type'] = $row->buy_person_type;
      $estimate_array[$x]['buy_submit_type'] = $row->buy_submit_type;
      $estimate_array[$x]['buy_period_type'] = $row->buy_period_type;
      $estimate_array[$x]['model_name'] = $row->model_name;
      $estimate_array[$x]['maker_name'] = $row->maker_name;
      $estimate_array[$x]['car_name'] = $row->car_name;
      $estimate_array[$x]['car_product_option'] = $row->car_product_option;
      $estimate_array[$x]['views'] = $row->views;
      $estimate_array[$x]['state'] = $row->state; //견적상태

      $end_date = strtotime($row->end_date);
      $today = strtotime(date("Ymd H:i:s"));
      $total_time = $end_date - $today;

      $years = floor($total_time/31536000);
      $days = floor($total_time/86400);
      $date = ($days -(365*$years))-1;
      $time = $total_time - ($days*86400);
      $hours = floor($time/3600);
      $time = $time - ($hours*3600);
      $min = floor($time/60);
      $sec = $time - ($min*60);

      // $diff = $end_date - $today;
      //
      // $hours = floor($diff/3600);
      // $diff = $diff - ($hours*3600);
      // $min = floor($diff/60);
      // $sec = $diff - ($min*60);
      //
      if($days < 0) {
				$date = "0";
				$hours = "00";
				$min = "00";
			}

      $data_array[$x]['end_date'] =	$date."일 ".$hours.":".$min; //남은 마감시간 계산

      $x++;
    }

    $y = 0;
    foreach($relieved_dealer_list as $row) {
      $relieved_dealer_array[$y]['estimate_submit_idx'] = $row->estimate_submit_idx;
      $relieved_dealer_array[$y]['etc_reg_name'] = $row->etc_reg_name;
      $relieved_dealer_array[$y]['lease_rate'] = $row->lease_rate;
      $relieved_dealer_array[$y]['bank_name'] = $row->bank_name;

      $member_name = $row->member_name;
      if(!empty($member_name)) {
        $len = mb_strlen($member_name);
        $member_name = mb_substr($member_name,0,1).str_repeat('*',$len-1); // P***
      }

      $relieved_dealer_array[$y]['member_name'] = $member_name;
      $relieved_dealer_array[$y]['work_place'] = $row->work_place;
      $relieved_dealer_array[$y]['dealer_idx'] = $row->member_idx;
      $relieved_dealer_array[$y]['member_img_path'] = $row->member_img_path;
      $relieved_dealer_array[$y]['member_grade'] = $row->member_grade;
      $relieved_dealer_array[$y]['area_name'] = $row->area_name;

      $y++;
    }

    //안심딜러
    $x = 0;
    foreach($dealer_list as $row) {
      $dealer_array[$x]['estimate_submit_idx'] = $row->estimate_submit_idx;
      $dealer_array[$x]['etc_reg_name'] = $row->etc_reg_name;
      $dealer_array[$x]['lease_rate'] = $row->lease_rate;
      $dealer_array[$x]['bank_name'] = $row->bank_name;

      $member_name = $row->member_name;

      if(!empty($member_name)) {
        $len = mb_strlen($member_name);
        $member_name = mb_substr($member_name,0,1).str_repeat('*',$len-1); // P***
      }
      $dealer_array[$x]['member_name'] = $member_name;

      $dealer_array[$x]['work_place'] = $row->work_place;
      $dealer_array[$x]['dealer_idx'] = $row->member_idx;
      $dealer_array[$x]['member_img_path'] = $row->member_img_path;
      $dealer_array[$y]['member_grade'] = $row->member_grade;
      $dealer_array[$y]['area_name'] = $row->area_name;

      $x++;
    }

    if($x == 0) {
      $response->code = "-2";
      $response->code_msg = $this->global_msg->code_msg('-2');
      $response->list_cnt = $x;
      $response->list_total_cnt = $dealer_list_count + count($relieved_dealer_list);
      $response->page_num = $page_num;
      $response->total_page =	$total_page;
      $response->estimate_array = $estimate_array;
      $response->dealer_array = $dealer_list;
      $response->relieved_dealer_array = $relieved_dealer_array;

    }else {
      $response->code = "1000";
      $response->code_msg = $this->global_msg->code_msg('1000');
      $response->list_cnt = $x;
      $response->list_total_cnt = $dealer_list_count + count($relieved_dealer_list);
      $response->page_num = $page_num;
      $response->total_page =	$total_page;
      $response->estimate_array = $estimate_array;
      $response->dealer_array = $dealer_list;

      if(!empty($relieved_dealer_list)) {
        $response->relieved_dealer_array = $relieved_dealer_array;
      }
    }

    echo json_encode($response);
    exit;
  }

  #내견적참여딜러상세조회
  public function estimate_submit_dealer_info(){

    header('Content-Type: application/json');

    $estimate_submit_idx = ($this->input->post("estimate_submit_idx", TRUE) != "")	?	$this->escstr($this->input->post("estimate_submit_idx", TRUE)) : "1";
    $data['estimate_submit_idx'] = $estimate_submit_idx;

    $response = new stdClass;

    $result = $this->model_estimate->estimate_submit_dealer_info($data);

    $response->code = "1000";
    $response->code_msg = "정상";
    $response->bank_name = $result->bank_idx;
    $response->bank_name = $result->bank_name;
    $response->interest_rate = $result->interest_rate;
    $response->interest_price = $result->interest_price;
    $response->lease_rate = $result->lease_rate;
    $response->lease_price = $result->lease_price;
    $response->bond_price = $result->bond_price;
    $response->etc_reg_name = $result->etc_reg_name;
    $response->etc_reg_price = $result->etc_reg_price;
    $response->consignment_price = $result->consignment_price;
    $response->moving_km = $result->moving_km;
    $response->moving_year = $result->moving_year;
    $response->used_car_price = $result->used_car_price;
    $response->estimate_submit_price = $result->estimate_submit_price;
    $response->estimate_idx = $result->estimate_idx;
    $response->member_grade = $result->member_grade;

    $member_name = $result->member_name;

    if(!empty($member_name) ) {
      if($result->dealer_select_state != "1") {
        $len = mb_strlen($member_name);
        $member_name = mb_substr($member_name,0,1).str_repeat('*',$len-1); // P***

      } else {
        $response->member_phone = $result->member_phone;
      }
    }

    $response->member_name = $member_name;
    $response->area_name = $result->area_name;

    $response->release_service_idx = $result->release_service_idx; //출고서비스 키값
    $response->release_service_name = $result->release_service_name; //출고서비스 이름

    $response->buy_person_type = $result->buy_person_type;
    $response->buy_person_name = $result->buy_person_name;
    $response->buy_submit_type = $result->buy_submit_type;
    $response->buy_submit_name = $result->buy_submit_name;
    $response->work_place = $result->work_place;
    $response->member_img_path = $result->member_img_path;

    echo json_encode($response);
    exit;
  }

  #내견적참여딜러 선택
  public function estimate_dealer_select(){

    header('Content-Type: application/json');

    $estimate_submit_idx = ($this->input->post("estimate_submit_idx", true) != "") ? $this->escstr($this->input->post("estimate_submit_idx", true)) : "";
    $estimate_idx = ($this->input->post("estimate_idx", true) != "") ? $this->escstr($this->input->post("estimate_idx", true)) : "";

    $data['estimate_submit_idx']=$estimate_submit_idx;
    $data['estimate_idx']=$estimate_idx;

    $result = $this->model_estimate->estimate_dealer_select($data); //내견적참여딜러 선택

    $response = new stdClass;

    if($result === "1"){
      $response->code = "1000";
      $response->code_msg = $this->global_msg->code_msg('1000');

      echo json_encode($response);
      exit;

    }else{
      $response->code = "-1";
      $response->code_msg = $this->global_msg->code_msg('-1');

      echo json_encode($response);
      exit;

    }
  }

  #견적취소
  public function estimate_cancel(){

    header('Content-Type: application/json');

    $estimate_idx = ($this->input->post("estimate_idx", true) != "") ? $this->escstr($this->input->post("estimate_idx", true)) : "";
    $member_idx = ($this->input->post("member_idx", true) != "") ? $this->escstr($this->input->post("member_idx", true)) : "";

    $data['estimate_idx']=$estimate_idx;
    $data['member_idx']=$member_idx;

    $result = $this->model_estimate->estimate_cancel($data); //경매취소

    $response = new stdClass;

    if($result === "1"){
      $response->code = "1000";
      $response->code_msg = $this->global_msg->code_msg('1000');

      echo json_encode($response);
      exit;

    }else{
      $response->code = "-1";
      $response->code_msg = $this->global_msg->code_msg('-1');

      echo json_encode($response);
      exit;

    }
  }

  #경매취소
  public function auction_cancel(){

    header('Content-Type: application/json');

    $estimate_submit_idx = ($this->input->post("estimate_submit_idx", true) != "") ? $this->escstr($this->input->post("estimate_submit_idx", true)) : "";
    $estimate_idx = ($this->input->post("estimate_idx", true) != "") ? $this->escstr($this->input->post("estimate_idx", true)) : "";
    $dealer_grade = ($this->input->post("dealer_grade", true) != "") ? $this->escstr($this->input->post("dealer_grade", true)) : "";
    $member_idx = ($this->input->post("member_idx", true) != "") ? $this->escstr($this->input->post("member_idx", true)) : "";

    $data['estimate_submit_idx']=$estimate_submit_idx;
    $data['estimate_idx']=$estimate_idx;
    $data['dealer_grade']=$dealer_grade;
    $data['member_idx']=$member_idx;

    $result = $this->model_estimate->auction_cancel($data); //경매취소

    $response = new stdClass;

    if($result === "1"){
      $response->code = "1000";
      $response->code_msg = $this->global_msg->code_msg('1000');

      echo json_encode($response);
      exit;

    }else{
      $response->code = "-1";
      $response->code_msg = $this->global_msg->code_msg('-1');

      echo json_encode($response);
      exit;

    }
  }

  #경매구매확정
  public function auction_confirm(){

    header('Content-Type: application/json');

    $estimate_submit_idx = ($this->input->post("estimate_submit_idx", true) != "") ? $this->escstr($this->input->post("estimate_submit_idx", true)) : "";
    $estimate_idx = ($this->input->post("estimate_idx", true) != "") ? $this->escstr($this->input->post("estimate_idx", true)) : "";
    $dealer_grade = ($this->input->post("dealer_grade", true) != "") ? $this->escstr($this->input->post("dealer_grade", true)) : "";
    $member_idx = ($this->input->post("member_idx", true) != "") ? $this->escstr($this->input->post("member_idx", true)) : "";

    $data['estimate_submit_idx']=$estimate_submit_idx;
    $data['estimate_idx']=$estimate_idx;
    $data['dealer_grade']=$dealer_grade;
    $data['member_idx'] = $member_idx;

    $result = $this->model_estimate->auction_confirm($data); //구매확정

    $response = new stdClass;

    if($result === "1"){
      $response->code = "1000";
      $response->code_msg = $this->global_msg->code_msg('1000');

      echo json_encode($response);
      exit;

    }else{
      $response->code = "-1";
      $response->code_msg = $this->global_msg->code_msg('-1');

      echo json_encode($response);
      exit;

    }
  }

}	// 클래스의 끝
?>

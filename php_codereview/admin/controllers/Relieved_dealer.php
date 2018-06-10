<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author :	박현진
| Create-Date : 2018-03-05
| Memo : 안심딜러 관리
|------------------------------------------------------------------------
*/

class Relieved_dealer extends MY_Controller{
	function __construct(){
		parent::__construct();

		$this->load->model('relieved_dealer/model_relieved_dealer');
		$this->load->model('member/model_member');
	}

 	//인덱스
	public function index(){
		$this->relieved_dealer_list();
	}

	#안심딜러 조회 화면
	public function relieved_dealer_list(){
		$this->_view('relieved_dealer/view_relieved_dealer_list',array());
	}

	#안심딜러 조회 리스트
	public function relieved_dealer_list_get(){

		$ins_s_date = ($this->input->post("ins_s_date", true) != "") ? $this->escstr($this->input->post("ins_s_date", true)) : "";
		$ins_e_date = ($this->input->post("ins_e_date", true) != "") ? $this->escstr($this->input->post("ins_e_date", true)) : "";
		$relieved_dealer_type = ($this->input->post("relieved_dealer_type", true) != "") ? $this->escstr($this->input->post("relieved_dealer_type", true)) : "";
		$start_s_date = ($this->input->post("start_s_date", true) != "") ? $this->escstr($this->input->post("start_s_date", true)) : "";
		$start_e_date = ($this->input->post("start_e_date", true) != "") ? $this->escstr($this->input->post("start_e_date", true)) : "";
		$end_s_date = ($this->input->post("end_s_date", true) != "") ? $this->escstr($this->input->post("end_s_date", true)) : "";
		$end_e_date = ($this->input->post("end_e_date", true) != "") ? $this->escstr($this->input->post("end_e_date", true)) : "";
		$search_text = ($this->input->post("search_text", true) != "") ? $this->escstr($this->input->post("search_text", true)) : "";
		$sort_category = ($this->input->post("sort_category", TRUE) != "")	?	$this->_escstr($this->input->post("sort_category", TRUE)) : "";
		$page_num = ($this->input->post("page_num", TRUE) != "")	?	$this->_escstr($this->input->post("page_num", TRUE)) : "1";

		$page_size=PAGESIZE;
		$data['page_size'] = $page_size;
		$data['page_no'] = ($page_num-1)*$page_size;

		$data['ins_s_date'] = $ins_s_date;
		$data['ins_e_date'] = $ins_e_date;
		$data['relieved_dealer_type'] = $relieved_dealer_type;
		$data['start_s_date'] = $start_s_date;
		$data['start_e_date'] = $start_e_date;
		$data['end_s_date'] = $end_s_date;
		$data['end_e_date'] = $end_e_date;
		$data['search_text'] = $search_text;
		$data['sort_category']		= $sort_category;

		$result_list = $this->model_relieved_dealer->member_list($data); //안심딜러 리스트 조회
		$result_list_count = $this->model_relieved_dealer->member_list_count($data); //안심딜러 리스트 카운트

		$no = $result_list_count-($page_size*($page_num-1));

		$paging = $this->global_function->paging($result_list_count,$page_size,$page_num,"relieved_dealer_list_get");

		$response = new stdClass();

		$response->result_list = $result_list;
		$response->result_list_count = $result_list_count;
		$response->no = $no;
		$response->paging = $paging;

		$this->_view2('relieved_dealer/view_relieved_dealer_list_get',$response);

	}

	#안심딜러 - 상세
	public function relieved_dealer_view(){

		$member_idx = ($this->input->get("member_idx", TRUE) != "")	?	$this->_escstr($this->input->get("member_idx", TRUE)) : "";

		$data['member_idx'] = $member_idx;

		$result = $this->model_relieved_dealer->member_view($data); //안심딜러 상세 조회

		$response = new stdClass();

		$response->result = $result;

		$this->_view('relieved_dealer/view_relieved_dealer_view',$response);
	}

	#안심딜러 - 등록화면
	public function relieved_dealer_reg(){

		$this->_view('relieved_dealer/view_relieved_dealer_reg',array());
	}

  #안심딜러 - 등록 기능
	public function relieved_dealer_reg_in(){

		$member_idx = ($this->input->post("member_idx", true) != "") ? $this->escstr($this->input->post("member_idx", true)) : "";
		$relieved_display_yn = ($this->input->post("relieved_display_yn", true) != "") ? $this->escstr($this->input->post("relieved_display_yn", true)) : "";
		$relieved_dealer_start_date = ($this->input->post("relieved_dealer_start_date", true) != "") ? $this->escstr($this->input->post("relieved_dealer_start_date", true)) : "";
		$relieved_dealer_end_date = ($this->input->post("relieved_dealer_end_date", true) != "") ? $this->escstr($this->input->post("relieved_dealer_end_date", true)) : "";

		$data['member_idx'] = $member_idx;
		$data['relieved_dealer_type'] = '1';
		$data['relieved_display_yn'] = $relieved_display_yn;
		$data['relieved_dealer_start_date'] = $relieved_dealer_start_date;
		$data['relieved_dealer_end_date'] = $relieved_dealer_end_date;

		$result = $this->model_relieved_dealer->relieved_dealer_reg_in($data); //안심 딜러로 등록

		if($result == "0") {
			echo json_encode(array('code' => 0, 'code_msg' => '안심 딜러 등록에 실패하였습니다.관리자에게 문의하세요.'));
			exit;
		} else {
			echo json_encode(array('code' => 1, 'code_msg' => '안심 딜러 등록에 성공하였습니다.'));
			exit;
		}
	}

  #안심딜러 - 수정 기능
	public function relieved_dealer_up_in(){

		$member_idx=($this->input->post("member_idx", true) != "") ? $this->escstr($this->input->post("member_idx", true)) : "";
		$relieved_display_yn=($this->input->post("relieved_display_yn", true) != "") ? $this->escstr($this->input->post("relieved_display_yn", true)) : "";
		$relieved_dealer_start_date=($this->input->post("relieved_dealer_start_date", true) != "") ? $this->escstr($this->input->post("relieved_dealer_start_date", true)) : "";
		$relieved_dealer_end_date=($this->input->post("relieved_dealer_end_date", true) != "") ? $this->escstr($this->input->post("relieved_dealer_end_date", true)) : "";

		$data['member_idx'] = $member_idx;
		$data['relieved_display_yn'] = $relieved_display_yn;
		$data['relieved_dealer_start_date'] = $relieved_dealer_start_date;
		$data['relieved_dealer_end_date'] = $relieved_dealer_end_date;

		$result = $this->model_relieved_dealer->relieved_dealer_up_in($data); //안심딜러 상태 수정

		if($result == "0") {
			echo json_encode(array('code' => 0, 'code_msg' => '안심 딜러 수정에 실패하였습니다.관리자에게 문의하세요.'));
			exit;
		} else {
			echo json_encode(array('code' => 1, 'code_msg' => '안심 딜러 수정에 성공하였습니다.'));
			exit;
		}
	}

	#안심딜러 - 삭제 기능
	public function relieved_del_in(){
		$member_idx = ($this->input->post("member_idx", true) != "") ? $this->_escstr($this->input->post("member_idx", true)) : "";

		$data['member_idx'] = $member_idx;

		$result = $this->model_relieved_dealer->relieved_del_in($data); //안심딜러 삭제

		if($result == "0") {
			echo json_encode(array('code' => 0, 'code_msg' => '안심 딜러 삭제에 실패하였습니다.관리자에게 문의하세요.'));
			exit;
		} else {
			echo json_encode(array('code' => 1, 'code_msg' => '안심 딜러에서 삭제하였습니다.'));
			exit;
		}
	}

	#안심딜러 - 딜러 선택 화면
	public function dealer_choise_list(){
		$this->_view_pop('relieved_dealer/popup_dealer_list',array());
	}

	#안심딜러 - 딜러 리스트 조회
	public function dealer_choise_list_get(){

		$member_id     		= ($this->input->post("member_id", TRUE) != "")	?	$this->_escstr($this->input->post("member_id", TRUE)) : "";
		$member_name   		= ($this->input->post("member_name", TRUE) != "")	?	$this->_escstr($this->input->post("member_name", TRUE)) : "";
		$member_phone  		= ($this->input->post("member_phone", TRUE) != "")	?	$this->_escstr($this->input->post("member_phone", TRUE)) : "";
		$member_gender 		= ($this->input->post("member_gender", TRUE) != "")	?	$this->_escstr($this->input->post("member_gender", TRUE)) : "";
		$search_s_date 		= ($this->input->post("search_s_date", TRUE) != "")	?	$this->_escstr($this->input->post("search_s_date", TRUE)) : "";
		$search_e_date 		= ($this->input->post("search_e_date", TRUE) != "")	?	$this->_escstr($this->input->post("search_e_date", TRUE)) : "";
		$birth_s_date  		= ($this->input->post("birth_s_date", TRUE) != "") ?	$this->_escstr($this->input->post("birth_s_date", TRUE)) : "";
		$birth_e_date  		= ($this->input->post("birth_e_date", TRUE) != "") ?	$this->_escstr($this->input->post("birth_e_date", TRUE)) : "";
		$search_age  	 		= ($this->input->post("search_age", TRUE) != "") ?	$this->_escstr($this->input->post("search_age", TRUE)) : "";
		$ticket_end_date	= ($this->input->post("ticket_end_date", TRUE) != "")	?	$this->_escstr($this->input->post("ticket_end_date", TRUE)) : "";
		$search_text 	 		= ($this->input->post("search_text", TRUE) != "")	?	$this->_escstr($this->input->post("search_text", TRUE)) : "";
		$area_idx 				= ($this->input->post("area_idx", TRUE) != "") ?	$this->_escstr($this->input->post("area_idx", TRUE)) : "";
		$member_accept_yn = ($this->input->post("member_accept_yn", TRUE) != "") ?	$this->_escstr($this->input->post("member_accept_yn", TRUE)) : "";
		$member_grade     = ($this->input->post("member_grade", TRUE) != "") ?	$this->_escstr($this->input->post("member_grade", TRUE)) : "";
		$del_yn        		= ($this->input->post("del_yn", TRUE) != "") ?	$this->_escstr($this->input->post("del_yn", TRUE)) : "";
		$sort_category	  = ($this->input->post("sort_category", TRUE) != "")	?	$this->_escstr($this->input->post("sort_category", TRUE)) : "";
		$nation_type	    = ($this->input->post("nation_type", TRUE) != "")	?	$this->_escstr($this->input->post("nation_type", TRUE)) : "";

		$page_num      		= ($this->input->post("page_num", TRUE) != "") ?	$this->_escstr($this->input->post("page_num", TRUE)) : "1";
		$page_size     		= PAGESIZE_15;

		$data['member_id']        = $member_id;
		$data['member_name']      = $member_name;
		$data['member_phone']     = $member_phone;
		$data['member_gender']    = $member_gender;
		$data['search_s_date']    = $search_s_date;
		$data['search_e_date']    = $search_e_date;
		$data['birth_s_date']     = $birth_s_date;
		$data['birth_e_date']     = $birth_e_date;
		$data['search_age']  	    = $search_age;
		$data['ticket_end_date']  = $ticket_end_date;
		$data['search_text']      = $search_text;
		$data['area_idx'] 			  = $area_idx;
		$data['member_accept_yn'] = $member_accept_yn;
		$data['member_grade']    	= $member_grade;
		$data['del_yn']        		= $del_yn;
		$data['member_type']	 		= '1'; // member_type 0:member 1:dealer
		$data['sort_category']		= $sort_category;
		$data['nation_type']		  = $nation_type;

		$data['page_size']     		= $page_size;
		$data['page_no']       		= ($page_num-1)*$page_size;

		$result_list 			 = $this->model_member->dealer_list_get($data); // 딜러 목록 가져오기
		$result_list_count = $this->model_member->dealer_list_count($data); // 딜러 목록 총 카운트

		$no                = $result_list_count-($page_size*($page_num-1));
		$paging            = $this->global_function->paging($result_list_count, $page_size, $page_num, "dealer_choise_list_get");

		# 문자열 인덱스 배열구조 생성
		$response = new stdclass();

		$response->result_list 				= $result_list;
		$response->result_list_count  = $result_list_count;
		$response->no                 = $no;
		$response->paging             = $paging;

		$this->_view2('relieved_dealer/popup_dealer_list_get', $response);
	}
}	// 클래스의 끝

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author :	박현진
| Create-Date : 2018-03-05
| Memo : 안심딜러 관리
|------------------------------------------------------------------------
*/

Class Model_relieved_dealer extends MY_Model {

	//딜러 목록 가져오기
	//member_type - 일반회원 : 0 , 딜러회원 : 1
	//relieved_dealer_type - 일반딜러 : 0 , 안심딜러 : 1
	public function member_list($data) {

		$page_size=(int)$data['page_size'];
    $page_no=(int)$data['page_no'];

		$ins_s_date = $data['ins_s_date'];
		$ins_e_date = $data['ins_e_date'];
		$relieved_dealer_type = $data['relieved_dealer_type'];
		$start_s_date = $data['start_s_date'];
		$start_e_date = $data['start_e_date'];
		$end_s_date = $data['end_s_date'];
		$end_e_date = $data['end_e_date'];
		$search_text = $data['search_text'];
		$sort_category = $data['sort_category'];

		$sql = "SELECT
							a.member_idx,
							a.member_type,
							a.member_join_type,
							FN_AES_DECRYPT(a.member_email) AS member_email,
							FN_AES_DECRYPT(a.member_name) AS member_name,
							FN_AES_DECRYPT(a.member_phone) AS member_phone,
							a.del_yn,
							a.relieved_dealer_type,
							a.relieved_display_yn,
							a.relieved_dealer_start_date,
							a.relieved_dealer_end_date
						FROM
							tbl_member a
						WHERE
							del_yn = 'N'
						AND member_type = 1
						AND relieved_dealer_type = 1
          	";

		if($relieved_dealer_type !=""){
      $sql .="AND a.relieved_dealer_type = '$relieved_dealer_type'";
    } else {
			$sql .="AND a.relieved_dealer_type = '1' ";
		}
		if($ins_s_date != ""){
			$sql .= " AND DATE(a.ins_date) >= '$ins_s_date' ";
		}
		if($ins_e_date != ""){
			$sql .= " AND DATE(a.ins_date) <= '$ins_e_date' ";
		}
		if($start_s_date != ""){
		$sql .= " AND DATE(a.relieved_dealer_start_date) >= '$start_s_date' ";
		}
		if($start_e_date != ""){
			$sql .= " AND DATE(a.relieved_dealer_start_date) <= '$start_e_date' ";
		}
		if($end_s_date != ""){
			$sql .= " AND DATE(a.relieved_dealer_end_date) >= '$end_s_date' ";
		}
		if($end_e_date != ""){
			$sql .= " AND DATE(a.relieved_dealer_end_date) <= '$end_e_date' ";
		}

    if($search_text !=""){
      $sql .=" AND (FN_AES_DECRYPT(a.member_name) LIKE '%$search_text%'
							 OR FN_AES_DECRYPT(a.member_id) LIKE '%$search_text%'
							 OR a.work_place LIKE '%$search_text%'
							)";
    }

		if($sort_category != "") {
			$sql .= " ORDER BY a.$sort_category ASC LIMIT ?, ? ";
		}else{
			$sql .= " ORDER BY a.ins_date DESC LIMIT ?, ? ";
		}

  	return $this->query_result($sql,
                                array(
																	$page_no,
                                  $page_size
																)
                              );
	}

	//회원 목록 총 카운트
	//member_type - 일반회원 : 0 , 딜러회원 : 1
	//relieved_dealer_type - 일반딜러 : 0 , 안심딜러 : 1
  public function member_list_count($data) {

		$ins_s_date = $data['ins_s_date'];
		$ins_e_date = $data['ins_e_date'];
		$relieved_dealer_type = $data['relieved_dealer_type'];
		$start_s_date = $data['start_s_date'];
		$start_e_date = $data['start_e_date'];
		$end_s_date = $data['end_s_date'];
		$end_e_date = $data['end_e_date'];
		$search_text = $data['search_text'];

		$sql = "SELECT
							COUNT(1) cnt
						FROM
							tbl_member a
						WHERE
							del_yn = 'N'
						AND member_type = 1
						AND relieved_dealer_type = 1
          ";

		if($relieved_dealer_type !=""){
	    $sql .="AND a.relieved_dealer_type = '$relieved_dealer_type'";
	  } else {
			$sql .="AND a.relieved_dealer_type = '$relieved_dealer_type' ";
		}

		if($ins_s_date != ""){
			$sql .= " AND DATE(a.ins_date) >= '$ins_s_date' ";
		}
		if($ins_e_date != ""){
			$sql .= " AND DATE(a.ins_date) <= '$ins_e_date' ";
		}
		if($start_s_date != ""){
		$sql .= " AND DATE(a.relieved_dealer_start_date) >= '$start_s_date' ";
		}
		if($start_e_date != ""){
			$sql .= " AND DATE(a.relieved_dealer_start_date) <= '$start_e_date' ";
		}
		if($end_s_date != ""){
			$sql .= " AND DATE(a.relieved_dealer_end_date) >= '$end_s_date' ";
		}
		if($end_e_date != ""){
			$sql .= " AND DATE(a.relieved_dealer_end_date) <= '$end_e_date' ";
		}

    return $this->query_cnt($sql,array());
  }

	//회원 상세 조회
	public function member_view($data) {
		$member_idx = $data['member_idx'];

		$sql = "SELECT
							a.member_idx,
							FN_AES_DECRYPT(a.member_name) AS member_name,
							a.relieved_dealer_type,
							a.relieved_display_yn,
							IFNULL(a.relieved_dealer_start_date,'') AS relieved_dealer_start_date,
							IFNULL(a.relieved_dealer_end_date,'') AS relieved_dealer_end_date
						FROM
							tbl_member a
						WHERE
							del_yn = 'N'
						AND member_type = 1
						AND relieved_dealer_type = 1
          	";
		$sql .= "AND member_idx = '$member_idx'";

  	return $this->query_row($sql,array());

	}

	//안심딜러 삭제
	public function relieved_del_in($data) {
		$member_idx = $data['member_idx'];

		$this->db->trans_begin();

		$sql = "UPDATE
							tbl_member
						SET
							relieved_dealer_type = '0'
						WHERE
							member_idx = ?
						";

		$this->query($sql,array($member_idx));

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return "0";
		}else{
			$this->db->trans_commit();
			return "1";
		}
	}

	//안심딜러 등록
	public function relieved_dealer_reg_in($data){

		$member_idx=$data['member_idx'];
		$relieved_dealer_type=$data['relieved_dealer_type'];
		$relieved_display_yn=$data['relieved_display_yn'];
		$relieved_dealer_start_date=$data['relieved_dealer_start_date'];
		$relieved_dealer_end_date=$data['relieved_dealer_end_date'];

		$this->db->trans_begin();

		 $sql = "UPDATE
							tbl_member
						SET
							relieved_dealer_type = '$relieved_dealer_type',
							relieved_display_yn = '$relieved_display_yn',
							relieved_dealer_start_date = '$relieved_dealer_start_date',
							relieved_dealer_end_date = '$relieved_dealer_end_date',
							upd_date = NOW()
						WHERE member_idx = '$member_idx'
			";

			$this->query($sql,array());

			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				return "0";
			}else{
				$this->db->trans_commit();
				return "1";
			}
	}

	//안심딜러 수정
	public function relieved_dealer_up_in($data){

		$member_idx=$data['member_idx'];
		$relieved_display_yn=$data['relieved_display_yn'];;
		$relieved_dealer_start_date=$data['relieved_dealer_start_date'];
		$relieved_dealer_end_date=$data['relieved_dealer_end_date'];

		$this->db->trans_begin();

		 $sql = "UPDATE
							tbl_member
						SET
							relieved_display_yn = '$relieved_display_yn',
							relieved_dealer_start_date = '$relieved_dealer_start_date',
							relieved_dealer_end_date = '$relieved_dealer_end_date',
							upd_date = NOW()
						WHERE member_idx = '$member_idx'
			";

			$this->query($sql,array());

			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				return "0";
			}else{
				$this->db->trans_commit();
				return "1";
			}
	}

}	// 클래스의 끝

?>

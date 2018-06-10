<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|------------------------------------------------------------------------
| Author :	박현진
| Create-Date : 2018-03-05
| Memo : 견적의뢰 관리 API
|------------------------------------------------------------------------
*/
class Model_estimate extends MY_Model{

  //제조사 리스트 조회
  public function maker_list($data) {
    $nation_type = $data['nation_type'];

    $sql = "SELECT
              a.nation_type,
              a.car_maker_idx,
              a.maker_name,
              a.img_path
            FROM
              tbl_car_maker a
            WHERE a.use_yn = 'Y'
            AND a.nation_type = ?
            ORDER BY order_no,maker_name ASC
           ";

    return $this->query_result($sql, array($nation_type));
  }

  //모델 리스트 조회
  public function model_list($data) {
    $car_maker_idx = $data['car_maker_idx'];

    $sql = "SELECT
              a.maker_name,
              b.car_model_idx,
              b.model_name,
              b.img_path
            FROM
              tbl_car_maker a JOIN tbl_car_model b ON a.car_maker_idx = b.car_maker_idx AND b.use_yn = 'Y'
            WHERE a.use_yn = 'Y'
            AND a.car_maker_idx = ?
            ";

    return $this->query_result($sql, array($car_maker_idx));
  }

  //모델별 가격범위
  public function model_price_range($data) {
    $car_model_idx = $data['car_model_idx'];

    $sql = "SELECT
              IFNULL(MAX(a.car_product_price),0) AS price_max,
              IFNULL(MIN(a.car_product_price),0) AS price_min
            FROM
              tbl_car_product a
            WHERE a.car_model_idx = ?
            GROUP BY a.car_model_idx
            ";

    return $this->query_row($sql, array($car_model_idx));
  }

  //세부모델 리스트 조회
  public function detail_model_list($data) {
    $car_model_idx = $data['car_model_idx'];

    $sql = "SELECT
              a.car_model_idx,
              a.model_name,
              b.car_idx,
              b.img_path,
              b.car_name,
              GROUP_CONCAT(c.car_product_idx) AS car_product_idx,
              GROUP_CONCAT(c.car_product_option) AS car_product_option,
              GROUP_CONCAT(c.car_product_price) AS car_product_price
            FROM
            tbl_car_model a JOIN tbl_car b ON a.car_model_idx = b.car_model_idx
            JOIN tbl_car_product c ON b.car_idx = c.car_idx
            WHERE a.use_yn = 'Y'
            AND a.car_model_idx = ?
            GROUP BY a.car_model_idx, a.model_name, b.car_idx, b.img_path, b.car_name
            ";

    return $this->query_result($sql, array($car_model_idx));
  }

  //세부모델 외관 색상 정보
  public function product_color_list($data) {
    $car_product_idx = $data['car_product_idx'];
    $color_type = $data['color_type'];

    $sql = "SELECT
              a.car_product_color_idx,
              a.color_type,
              a.color_name,
              a.color_price,
              a.img_path
            FROM
              tbl_car_product_color a
            WHERE a.del_yn = 'N'
            AND a.display_yn = 'Y'
            AND a.color_type = ?
            AND a.car_product_idx = ?
            ";

    return $this->query_result($sql, array($color_type, $car_product_idx));
  }

  //차량정보조회
  public function car_product_info($data) {
    $car_product_idx = $data['car_product_idx'];

    $sql = "SELECT
              a.car_product_idx,
              a.car_product_option,
              a.car_product_price,
              b.car_name,
              b.car_price,
              b.point_yn,
              b.img_path,
              c.model_name,
              d.maker_name,
              d.nation_type
            FROM
              tbl_car_product a JOIN tbl_car b ON a.car_idx = b.car_idx AND b.use_yn = 'Y'
              JOIN tbl_car_model c ON b.car_model_idx = c.car_model_idx AND c.use_yn = 'Y'
              JOIN tbl_car_maker d ON c.car_maker_idx = d.car_maker_idx AND d.use_yn = 'Y'
            WHERE a.display_yn = 'Y'
            AND a.del_yn = 'N'
            AND a.car_product_idx = ?
           ";

    return $this->query_row($sql, array($car_product_idx));
  }

  //도시 리스트 조회
  public function city_list() {

    $sql = "SELECT
              a.city_cd_idx,
              a.city_cd,
              a.city_name
            FROM
              tbl_city_cd a
            WHERE a.del_yn = 'N'
            ORDER BY order_no ASC
            ";

    return $this->query_result($sql, array());
  }

  //지역 리스트 조회
  public function region_list($data) {
    $city_cd = $data['city_cd'];

    $sql = "SELECT
              a.region_cd_idx,
              a.region_name
            FROM
              tbl_region_cd a
            WHERE a.del_yn = 'N'
            AND city_cd = ?
            ORDER BY order_no ASC
            ";

    return $this->query_result($sql, array($city_cd));
  }

  //추가 할인, 출고서비스,금융사 정보
  public function category_management_list($data) {
    $type = $data['type'];

    $sql = "SELECT
              a.category_management_idx,
              a.category_name
            FROM
              tbl_category_management a
            WHERE a.del_yn = 'N'
            AND a.state = '1'
            AND type = ?
            ORDER BY order_no ASC
            ";

    return $this->query_result($sql, array($type));
  }

  public function add_option_list() {
    $sql = "SELECT
						  car_product_option_idx,
							option_name,
							option_price,
							contents
						FROM
							tbl_car_product_option
						WHERE
							del_yn = 'N'
            AND display_yn = 'Y'
				";

	  return	$this->query_result($sql,array());
  }

  //견적등록
  public function estimate_reg_in($data){

    $member_idx=$data['member_idx'];
    $nation_type=$data['nation_type'];
    $car_product_idx=$data['car_product_idx'];
    $outer_color_idx=$data['outer_color_idx'];
    $outer_color=$data['outer_color'];
    $outer_color_price=$data['outer_color_price'];
    $inner_color_idx=$data['inner_color_idx'];
    $inner_color=$data['inner_color'];
    $inner_color_price=$data['inner_color_price'];
    $car_product_option_idx=$data['car_product_option_idx'];
    $car_product_option_name=$data['car_product_option_name'];
    $car_product_option_price=$data['car_product_option_price'];
    $hyundai_point_yn=$data['hyundai_point_yn'];
    $first_area_idx=$data['first_area_idx'];
    $first_area_name=$data['first_area_name'];
    $second_area_idx=$data['second_area_idx'];
    $second_area_name=$data['second_area_name'];
    $add_reduce_idx=$data['add_reduce_idx'];
    $add_reduce_name=$data['add_reduce_name'];
    $release_service_idx=$data['release_service_idx'];
    $release_service_name=$data['release_service_name'];
    $reduce_request_price=$data['reduce_request_price'];
    $end_date=$data['end_date'];
    $buy_person_type=$data['buy_person_type'];
    $buy_submit_type=$data['buy_submit_type'];
    $buy_period_type=$data['buy_period_type'];
    $buy_bond_type=$data['buy_bond_type'];
    $buy_prepayment_rate=$data['buy_prepayment_rate'];
    $buy_prepayment_price=$data['buy_prepayment_price'];
    $buy_deposit_rate=$data['buy_deposit_rate'];
    $buy_deposit_price=$data['buy_deposit_price'];
    $buy_bank_idx=$data['buy_bank_idx'];
    $buy_bank_name=$data['buy_bank_name'];
    $buy_remains_rate=$data['buy_remains_rate'];
    $buy_remains_price=$data['buy_remains_price'];
    $buy_mycar_type=$data['buy_mycar_type'];
    $buy_mycar_name=$data['buy_mycar_name'];
    $buy_mycar_number=$data['buy_mycar_number'];
    $buy_mycar_body_number=$data['buy_mycar_body_number'];
    $buy_mycar_moving_distance=$data['buy_mycar_moving_distance'];
    $buy_mycar_mission=$data['buy_mycar_mission'];
    $buy_mycar_option=$data['buy_mycar_option'];
    $buy_mycar_color=$data['buy_mycar_color'];
    $buy_mycar_selling_area=$data['buy_mycar_selling_area'];
    $buy_mycar_buy_type=$data['buy_mycar_buy_type'];
    $buy_mycar_accident=$data['buy_mycar_accident'];
    $buy_mycar_img_1=$data['buy_mycar_img_1'];
    $buy_mycar_img_2=$data['buy_mycar_img_2'];
    $buy_mycar_img_3=$data['buy_mycar_img_3'];
    $buy_mycar_img_4=$data['buy_mycar_img_4'];
    $buy_mycar_img_5=$data['buy_mycar_img_5'];
    $buy_mycar_img_6=$data['buy_mycar_img_6'];
    $estimated_amount=$data['estimated_amount'];

    $this->db->trans_begin();

    $sql = "INSERT INTO
              tbl_estimate
            (
            member_idx,
            nation_type,
            car_product_idx,
            outer_color_idx,
            outer_color,
            outer_color_price,
            inner_color_idx,
            inner_color,
            inner_color_price,
            car_product_option_idx,
            car_product_option_name,
            car_product_option_price,
            hyundai_point_yn,
            first_area_idx,
            first_area_name,
            second_area_idx,
            second_area_name,
            add_reduce_idx,
            add_reduce_name,
            release_service_idx,
            release_service_name,
            reduce_request_price,
            end_date,
            buy_person_type,
            buy_submit_type,
            buy_period_type,
            buy_bond_type,
            buy_prepayment_rate,
            buy_prepayment_price,
            buy_deposit_rate,
            buy_deposit_price,
            buy_bank_idx,
            buy_bank_name,
            buy_remains_rate,
            buy_remains_price,
            buy_mycar_type,
            buy_mycar_name,
            buy_mycar_number,
            buy_mycar_body_number,
            buy_mycar_moving_distance,
            buy_mycar_mission,
            buy_mycar_option,
            buy_mycar_color,
            buy_mycar_selling_area,
            buy_mycar_buy_type,
            buy_mycar_accident,
            buy_mycar_img_1,
            buy_mycar_img_2,
            buy_mycar_img_3,
            buy_mycar_img_4,
            buy_mycar_img_5,
            buy_mycar_img_6,
            estimated_amount,
            state,
            del_yn,
            ins_date,
            upd_date
            )
              VALUES
            (
            ?, -- member_idx
            ?, -- nation_type
            ?, -- car_product_idx
            ?, -- outer_color_idx
            ?, -- outer_color
            ?, -- outer_color_price
            ?, -- inner_color_idx
            ?, -- inner_color
            ?, -- inner_color_price
            ?, -- car_product_option_idx
            ?, -- car_product_option_name
            ?, -- car_product_option_price
            ?, -- hyundai_point_yn
            ?, -- first_area_idx
            ?, -- first_area_name
            ?, -- second_area_idx
            ?, -- second_area_name
            ?, -- add_reduce_idx
            ?, -- add_reduce_name
            ?, -- release_service_idx
            ?, -- release_service_name
            ?, -- reduce_request_price
            ?, -- end_date
            ?, -- buy_person_type
            ?, -- buy_submit_type
            ?, -- buy_period_type
            ?, -- buy_bond_type
            ?, -- buy_prepayment_rate
            ?, -- buy_prepayment_price
            ?, -- buy_deposit_rate
            ?, -- buy_deposit_price
            ?, -- buy_bank_idx
            ?, -- buy_bank_name
            ?, -- buy_remains_rate
            ?, -- buy_remains_price
            ?, -- buy_mycar_type
            ?, -- buy_mycar_name
            ?, -- buy_mycar_number
            ?, -- buy_mycar_body_number
            ?, -- buy_mycar_moving_distance
            ?, -- buy_mycar_mission
            ?, -- buy_mycar_option
            ?, -- buy_mycar_color
            ?, -- buy_mycar_selling_area
            ?, -- buy_mycar_buy_type
            ?, -- buy_mycar_accident
            ?, -- buy_mycar_img_1
            ?, -- buy_mycar_img_2
            ?, -- buy_mycar_img_3
            ?, -- buy_mycar_img_4
            ?, -- buy_mycar_img_5
            ?, -- buy_mycar_img_6
            ?, -- estimated_amount
            '1', -- state
            'N', -- del_yn
            NOW(), -- ins_date
            NOW() -- upd_date
            )
            ";

    $this->query($sql,
                  array(
                    (int)$member_idx,
                    $nation_type,
                    (int)$car_product_idx,
                    (int)$outer_color_idx,
                    $outer_color,
                    (int)$outer_color_price,
                    (int)$inner_color_idx,
                    $inner_color,
                    (int)$inner_color_price,
                    (int)$car_product_option_idx,
                    $car_product_option_name,
                    (int)$car_product_option_price,
                    $hyundai_point_yn,
                    (int)$first_area_idx,
                    $first_area_name,
                    (int)$second_area_idx,
                    $second_area_name,
                    (int)$add_reduce_idx,
                    $add_reduce_name,
                    $release_service_idx,
                    $release_service_name,
                    (int)$reduce_request_price,
                    $end_date,
                    $buy_person_type,
                    $buy_submit_type,
                    $buy_period_type,
                    $buy_bond_type,
                    $buy_prepayment_rate,
                    (int)$buy_prepayment_price,
                    $buy_deposit_rate,
                    (int)$buy_deposit_price,
                    (int)$buy_bank_idx,
                    $buy_bank_name,
                    $buy_remains_rate,
                    (int)$buy_remains_price,
                    $buy_mycar_type,
                    $buy_mycar_name,
                    $buy_mycar_number,
                    $buy_mycar_body_number,
                    $buy_mycar_moving_distance,
                    $buy_mycar_mission,
                    $buy_mycar_option,
                    $buy_mycar_color,
                    $buy_mycar_selling_area,
                    $buy_mycar_buy_type,
                    $buy_mycar_accident,
                    $buy_mycar_img_1,
                    $buy_mycar_img_2,
                    $buy_mycar_img_3,
                    $buy_mycar_img_4,
                    $buy_mycar_img_5,
                    $buy_mycar_img_6,
                    (int)$estimated_amount
                  ),$data
                );

    if($this->db->trans_status() === FALSE){

      $this->db->trans_rollback();
      return "0";
    }else{

      $this->db->trans_commit();
      return "1";
    }
  }

  public function estimate_up_in($data){

    $estimate_idx=$data['estimate_idx'];
    $member_idx=$data['member_idx'];
    $nation_type=$data['nation_type'];
    $car_product_idx=$data['car_product_idx'];
    $outer_color_idx=$data['outer_color_idx'];
    $outer_color=$data['outer_color'];
    $outer_color_price=$data['outer_color_price'];
    $inner_color_idx=$data['inner_color_idx'];
    $inner_color=$data['inner_color'];
    $inner_color_price=$data['inner_color_price'];
    $car_product_option_idx=$data['car_product_option_idx'];
    $car_product_option_name=$data['car_product_option_name'];
    $car_product_option_price=$data['car_product_option_price'];
    $hyundai_point_yn=$data['hyundai_point_yn'];
    $first_area_idx=$data['first_area_idx'];
    $first_area_name=$data['first_area_name'];
    $second_area_idx=$data['second_area_idx'];
    $second_area_name=$data['second_area_name'];
    $add_reduce_idx=$data['add_reduce_idx'];
    $add_reduce_name=$data['add_reduce_name'];
    $release_service_idx=$data['release_service_idx'];
    $release_service_name=$data['release_service_name'];
    $reduce_request_price=$data['reduce_request_price'];
    $end_date=$data['end_date'];
    $buy_person_type=$data['buy_person_type'];
    $buy_submit_type=$data['buy_submit_type'];
    $buy_period_type=$data['buy_period_type'];
    $buy_bond_type=$data['buy_bond_type'];
    $buy_prepayment_rate=$data['buy_prepayment_rate'];
    $buy_prepayment_price=$data['buy_prepayment_price'];
    $buy_deposit_rate=$data['buy_deposit_rate'];
    $buy_deposit_price=$data['buy_deposit_price'];
    $buy_bank_idx=$data['buy_bank_idx'];
    $buy_bank_name=$data['buy_bank_name'];
    $buy_remains_rate=$data['buy_remains_rate'];
    $buy_remains_price=$data['buy_remains_price'];
    $buy_mycar_type=$data['buy_mycar_type'];
    $buy_mycar_name=$data['buy_mycar_name'];
    $buy_mycar_number=$data['buy_mycar_number'];
    $buy_mycar_body_number=$data['buy_mycar_body_number'];
    $buy_mycar_moving_distance=$data['buy_mycar_moving_distance'];
    $buy_mycar_mission=$data['buy_mycar_mission'];
    $buy_mycar_option=$data['buy_mycar_option'];
    $buy_mycar_color=$data['buy_mycar_color'];
    $buy_mycar_selling_area=$data['buy_mycar_selling_area'];
    $buy_mycar_buy_type=$data['buy_mycar_buy_type'];
    $buy_mycar_accident=$data['buy_mycar_accident'];
    $buy_mycar_img_1=$data['buy_mycar_img_1'];
    $buy_mycar_img_2=$data['buy_mycar_img_2'];
    $buy_mycar_img_3=$data['buy_mycar_img_3'];
    $buy_mycar_img_4=$data['buy_mycar_img_4'];
    $buy_mycar_img_5=$data['buy_mycar_img_5'];
    $buy_mycar_img_6=$data['buy_mycar_img_6'];
    $estimated_amount=$data['estimated_amount'];

    $this->db->trans_begin();

    $sql = "UPDATE
              tbl_estimate
            SET
              member_idx=?,
              nation_type=?,
              car_product_idx=?,
              outer_color_idx=?,
              outer_color=?,
              outer_color_price=?,
              inner_color_idx=?,
              inner_color=?,
              inner_color_price=?,
              car_product_option_idx=?,
              car_product_option_name=?,
              car_product_option_price=?,
              hyundai_point_yn=?,
              first_area_idx=?,
              first_area_name=?,
              second_area_idx=?,
              second_area_name=?,
              add_reduce_idx=?,
              add_reduce_name=?,
              release_service_idx=?,
              release_service_name=?,
              reduce_request_price=?,
              end_date=?,
              buy_person_type=?,
              buy_submit_type=?,
              buy_period_type=?,
              buy_bond_type=?,
              buy_prepayment_rate=?,
              buy_prepayment_price=?,
              buy_deposit_rate=?,
              buy_deposit_price=?,
              buy_bank_idx=?,
              buy_bank_name=?,
              buy_remains_rate=?,
              buy_remains_price=?,
              buy_mycar_type=?,
              buy_mycar_name=?,
              buy_mycar_number=?,
              buy_mycar_body_number=?,
              buy_mycar_moving_distance=?,
              buy_mycar_mission=?,
              buy_mycar_option=?,
              buy_mycar_color=?,
              buy_mycar_selling_area=?,
              buy_mycar_buy_type=?,
              buy_mycar_accident=?,
              buy_mycar_img_1=?,
              buy_mycar_img_2=?,
              buy_mycar_img_3=?,
              buy_mycar_img_4=?,
              buy_mycar_img_5=?,
              buy_mycar_img_6=?,
              estimated_amount=?,
              del_yn='N',
              upd_date=NOW()
            WHERE estimate_idx=?
            ";

    $this->query($sql,
                  array(
                    (int)$member_idx,
                    $nation_type,
                    (int)$car_product_idx,
                    (int)$outer_color_idx,
                    $outer_color,
                    (int)$outer_color_price,
                    (int)$inner_color_idx,
                    $inner_color,
                    (int)$inner_color_price,
                    (int)$car_product_option_idx,
                    $car_product_option_name,
                    (int)$car_product_option_price,
                    $hyundai_point_yn,
                    (int)$first_area_idx,
                    $first_area_name,
                    (int)$second_area_idx,
                    $second_area_name,
                    (int)$add_reduce_idx,
                    $add_reduce_name,
                    (int)$release_service_idx,
                    $release_service_name,
                    (int)$reduce_request_price,
                    $end_date,
                    $buy_person_type,
                    $buy_submit_type,
                    $buy_period_type,
                    $buy_bond_type,
                    $buy_prepayment_rate,
                    (int)$buy_prepayment_price,
                    $buy_deposit_rate,
                    (int)$buy_deposit_price,
                    (int)$buy_bank_idx,
                    $buy_bank_name,
                    $buy_remains_rate,
                    (int)$buy_remains_price,
                    $buy_mycar_type,
                    $buy_mycar_name,
                    $buy_mycar_number,
                    $buy_mycar_body_number,
                    $buy_mycar_moving_distance,
                    $buy_mycar_mission,
                    $buy_mycar_option,
                    $buy_mycar_color,
                    $buy_mycar_selling_area,
                    $buy_mycar_buy_type,
                    $buy_mycar_accident,
                    $buy_mycar_img_1,
                    $buy_mycar_img_2,
                    $buy_mycar_img_3,
                    $buy_mycar_img_4,
                    $buy_mycar_img_5,
                    $buy_mycar_img_6,
                    (int)$estimated_amount,
                    $estimate_idx
                  )
                );


    $sql = "UPDATE
              tbl_estimate_submit
            SET
              state=3,
              del_yn='Y',
              upd_date=NOW()
            WHERE estimate_idx=?
            ";

    $this->query($sql,array($estimate_idx));

    if($this->db->trans_status() === FALSE){

      $this->db->trans_rollback();
      return "0";
    }else{

      $this->db->trans_commit();
      return "1";
    }
  }

  //견적 신청 내역 리스트 조회
  public function estimate_request_history($data) {
    $member_idx = $data['member_idx'];
    $page_num = $data['page_num'];
    $page_size = $data['page_size'];

    $sql = "SELECT
              a.estimate_idx,
              a.first_area_idx,
              a.first_area_name,
              (CASE a.buy_person_type WHEN 0 THEN '개인/개인사업자' WHEN 1 THEN '법인' END )AS buy_person_type,
              (CASE a.buy_submit_type WHEN 0 THEN '현금' WHEN 1 THEN '리스' WHEN 2 THEN '할부' WHEN 3 THEN '렌트' END )AS buy_submit_type,
              a.buy_period_type,
              a.views,
              a.end_date,
              b.car_product_option,
              c.car_name,
              d.model_name,
              e.maker_name,
              a.state
            FROM
              tbl_estimate a JOIN tbl_car_product b ON a.car_product_idx = b.car_product_idx
              JOIN tbl_car c ON b.car_idx = c.car_idx AND c.use_yn = 'Y'
              JOIN tbl_car_model d ON c.car_model_idx = d.car_model_idx AND d.use_yn = 'Y'
              JOIN tbl_car_maker e ON d.car_maker_idx = e.car_maker_idx AND e.use_yn = 'Y'
            WHERE a.del_yn = 'N'
            AND a.state IN(1,3,4,5)
            AND a.member_idx = ?
          ";

    if($page_num !="" && $page_size != ""){
      $sql .=" LIMIT $page_num,$page_size ";
    }

    return $this->query_result($sql, array($member_idx));
  }

  //견적 신청 내역 리스트 조회
  public function estimate_request_history_count($data) {
    $member_idx = $data['member_idx'];

    $sql = "SELECT
              COUNT(1) cnt
            FROM
              tbl_estimate a JOIN tbl_car_product b ON a.car_product_idx = b.car_product_idx
              JOIN tbl_car c ON b.car_idx = c.car_idx AND c.use_yn = 'Y'
              JOIN tbl_car_model d ON c.car_model_idx = d.car_model_idx AND d.use_yn = 'Y'
              JOIN tbl_car_maker e ON d.car_maker_idx = e.car_maker_idx AND e.use_yn = 'Y'
            WHERE a.del_yn = 'N'
            AND a.state IN(1,3,4,5)
            AND a.member_idx = ?
            ";

    return $this->query_cnt($sql, array($member_idx));
  }

  //내견적참여딜러(일반딜러)
  public function estimate_submit_dealer_list($data) {
    $estimate_idx = $data['estimate_idx'];
    $page_num = $data['page_num'];
    $page_size = $data['page_size'];

    $member_idx = $data['member_idx'];

    $sql = "SELECT
              a.estimate_idx,
              b.state,
              b.estimate_submit_idx,
              b.etc_reg_name,
              b.lease_rate,
              b.bank_name,
              b.release_service_idx,
              b.release_service_name,
              b.state AS dealer_select_state,
              c.member_idx,
              FN_AES_DECRYPT(c.member_name) AS member_name,
              c.work_place,
              c.work_place_addr,
              c.member_img_path,
              c.member_grade,
              c.member_phone,
              c.area_name
            FROM
              tbl_estimate a JOIN tbl_estimate_submit b ON a.estimate_idx = b.estimate_idx
              JOIN tbl_member c ON b.dealer_idx = c.member_idx
            WHERE a.estimate_idx = ?
            AND c.relieved_dealer_type = 0
            ";
    if($member_idx !=""){
      $sql .=" AND c.member_idx = '$member_idx' ";
    }

    if($page_num !="" && $page_size != ""){
      $sql .=" LIMIT $page_num,$page_size ";
    }

    return $this->query_result($sql, array($estimate_idx));
  }

  //내견적참여딜러(일반딜러)
  public function estimate_submit_dealer_list_count($data) {
    $estimate_idx = $data['estimate_idx'];
    $member_idx = $data['member_idx'];

    $sql = "SELECT
              COUNT(1) cnt
            FROM
              tbl_estimate a JOIN tbl_estimate_submit b ON a.estimate_idx = b.estimate_idx
              JOIN tbl_member c ON b.dealer_idx = c.member_idx
            WHERE a.estimate_idx = ?
            AND c.relieved_dealer_type = 0
            ";

    if($member_idx !=""){
      $sql .=" AND c.member_idx = '$member_idx' ";
    }

    return $this->query_cnt($sql, array($estimate_idx));
  }

  //내견적참여딜러(안심딜러)
  public function estimate_submit_relieved_dealer_list($data) {
    $estimate_idx = $data['estimate_idx'];
    $member_idx = $data['member_idx'];

    $sql = "SELECT
              a.estimate_idx,
              b.estimate_submit_idx,
              b.etc_reg_name,
              b.lease_rate,
              b.bank_name,
              b.state,
              b.state AS dealer_select_state,
              c.member_idx,
              FN_AES_DECRYPT(c.member_name) AS member_name,
              c.work_place,
              c.work_place_addr,
              c.member_img_path,
              c.member_grade,
              c.member_phone,
              c.area_name
            FROM
              tbl_estimate a JOIN tbl_estimate_submit b ON a.estimate_idx = b.estimate_idx
              JOIN tbl_member c ON b.dealer_idx = c.member_idx
            WHERE a.estimate_idx = ?
            AND c.relieved_dealer_start_date <= CURDATE()
            AND c.relieved_dealer_end_date >= CURDATE()
            AND c.relieved_dealer_type = 1

            ";

    if($member_idx !=""){
      $sql .=" AND c.member_idx = '$member_idx' ";
    }
    $sql .=" ORDER BY member_grade DESC";

    return $this->query_result($sql, array($estimate_idx));
  }

  //내견적참여딜러(딜러전부)
  public function estimate_submit_dealer_list_total_count($data) {
    $member_idx = $data['member_idx'];

    $sql = "SELECT
              COUNT(1) cnt
              FROM tbl_estimate a JOIN tbl_estimate_submit b ON a.estimate_idx = b.estimate_idx
            WHERE a.member_idx = ?
            ";

    return $this->query_cnt($sql, array($member_idx));
  }

  //내견적참여딜러 상세정보
  public function estimate_submit_dealer_info($data) {
    $estimate_submit_idx = $data['estimate_submit_idx'];

    $sql = "SELECT
              a.estimate_submit_idx,
              a.interest_rate,
              a.interest_price,
              a.lease_rate,
              a.lease_price,
              a.bond_price,
              a.etc_reg_name,
              a.etc_reg_price,
              a.consignment_price,
              a.moving_km,
              a.moving_year,
              a.used_car_price,
              a.bank_idx,
              a.bank_name,
              a.estimate_submit_price,
              a.state AS dealer_select_state,
              b.release_service_idx,
              b.release_service_name,
              b.estimate_idx,
              b.buy_person_type,
              (CASE b.buy_person_type WHEN 0 THEN '개인/개인사업자' WHEN 1 THEN '법인' END )AS buy_person_name,
              b.buy_submit_type,
              (CASE b.buy_submit_type WHEN 0 THEN '현금' WHEN 1 THEN '리스' WHEN 2 THEN '할부' WHEN 3 THEN '렌트' END )AS buy_submit_name,
              b.end_date,
              FN_AES_DECRYPT(c.member_name) AS member_name,
              FN_AES_DECRYPT(c.member_phone) AS member_phone,
              c.member_grade,
              c.area_name,
              c.member_img_path,
              c.work_place
            FROM
              tbl_estimate_submit a JOIN tbl_estimate b ON a.estimate_idx = b.estimate_idx AND b.del_yn = 'N'
              JOIN tbl_member c ON a.dealer_idx = c.member_idx AND c.del_yn = 'N'
            WHERE
              a.del_yn = 'N'
              AND a.estimate_submit_idx = ?
            ";

    return	$this->query_row($sql,array($estimate_submit_idx),$data);
  }

  //내견적에 참여한 딜러중 조건에 부합하는 딜러 선택
  public function estimate_dealer_select($data) {

    $estimate_submit_idx = $data['estimate_submit_idx'];
    $estimate_idx = $data['estimate_idx'];

    $this->db->trans_begin();

    //내 견적을 선택 state : 5 로 변경
    $sql = "UPDATE
              tbl_estimate
						SET
              state = '5',
              upd_date = NOW()
						WHERE
							estimate_idx = '$estimate_idx'
           ";
    $this->query($sql,array());

    $sql = "SELECT
              estimate_submit_idx
						FROM
              tbl_estimate_submit
						WHERE
							estimate_idx = ?
           ";
    $result_list = $this->query_result($sql,array($estimate_idx));

    //내 견적에 참여한 딜러의 경매정보를 선택 state : 1 로 변경
    $sql = "UPDATE
              tbl_estimate_submit
						SET
              state = '1',
              upd_date = NOW()
						WHERE
							estimate_submit_idx = '$estimate_submit_idx'
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

  //선택한 딜러가 있을 경우의 경매 취소
  public function auction_cancel($data) {

    $estimate_submit_idx = $data['estimate_submit_idx'];
    $estimate_idx = $data['estimate_idx'];
    $dealer_grade = $data['dealer_grade'];
    $member_idx = $data['member_idx'];

    $this->db->trans_begin();

    //경매 취소 state : 6으로 변경
    $sql = "UPDATE
              tbl_estimate
						SET
              state = '6',
              upd_date = NOW()
						WHERE
							estimate_idx = '$estimate_idx'
           ";
    $this->query($sql,array());

    //경매 키값에 해당하는 경매 참여 정보를 전부 삭제하고 취소 state : 5로 변경
    //별점 점수 함께 수정
    $sql = "UPDATE
              tbl_estimate_submit
						SET
              state = '5',
              del_yn = 'Y',
              dealer_grade = '$dealer_grade',
              upd_date = NOW()
						WHERE
							estimate_submit_idx = '$estimate_submit_idx'
              AND dealer_idx = '$member_idx'
           ";
    $this->query($sql,array());

    //경매 취소 시 받은 별점을, 딜러의 별점에 합산하여 계산해서 수정
    $sql = "UPDATE
              tbl_member
						SET
              member_grade = (SELECT
                                SUM(dealer_grade) / COUNT(1)
                              FROM tbl_estimate_submit
                              WHERE
                                del_yn = 'N'
                              AND state IN (3,4)
                              AND dealer_idx = '$member_idx'
                            ),
              upd_date = NOW()
						WHERE
							member_idx = '$member_idx'
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

  //선택한 딜러가 없을 경우의 견적 취소
  public function estimate_cancel($data) {

    $estimate_idx = $data['estimate_idx'];
    $member_idx = $data['member_idx'];

    $this->db->trans_begin();

    $sql = "UPDATE
              tbl_estimate
						SET
              state = '2',
              upd_date = NOW()
						WHERE
							estimate_idx = '$estimate_idx'
           ";
    $this->query($sql,array());

    $sql = "UPDATE
              tbl_estimate_submit
						SET
              state = '3',
              del_yn = 'Y',
              upd_date = NOW()
						WHERE
							estimate_idx = '$estimate_idx'
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

  //구매확정
  public function auction_confirm($data) {

    $estimate_submit_idx = $data['estimate_submit_idx'];
    $estimate_idx = $data['estimate_idx'];
    $dealer_grade = $data['dealer_grade'];
    $member_idx = $data['member_idx'];

    $this->db->trans_begin();

    //견적정보를 구매확정 state : 4로 변경
    $sql = "UPDATE
              tbl_estimate
						SET
              state = '4',
              upd_date = NOW()
						WHERE
							estimate_idx = '$estimate_idx'
           ";
    $this->query($sql,array());

    //경매정보에 선택된 딜러의 경매 정보를 구매확정 state : 4로 변경, 별점도 추가
    $sql = "UPDATE
              tbl_estimate_submit
						SET
              state = '4',
              dealer_grade = '$dealer_grade',
              upd_date = NOW()
						WHERE
							estimate_submit_idx = '$estimate_submit_idx'
           ";
    $this->query($sql,array());

    //구매확정시 받은 별점과 기존 딜러가 가지고 있는 별점을 합산하여 다시 수정
    $sql = "UPDATE
              tbl_member
            SET
              member_grade = (SELECT
                                SUM(dealer_grade) / COUNT(1)
                              FROM tbl_estimate_submit
                              WHERE
                                del_yn = 'N'
                              AND state IN (3,4)
                              AND dealer_idx = '$member_idx'
                            ),
              upd_date = NOW()
            WHERE
              member_idx = '$member_idx'
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

  //견적 정보 조회
  public function estimate_info($data) {
    $estimate_idx = $data['estimate_idx'];

    $sql = "SELECT
              a.buy_person_type,
              (CASE a.buy_person_type WHEN 0 THEN '개인/개인사업자' WHEN 1 THEN '법인' END )AS buy_person_name,
              a.buy_submit_type,
              (CASE a.buy_submit_type WHEN 0 THEN '현금' WHEN 1 THEN '리스' WHEN 2 THEN '할부' WHEN 3 THEN '렌트' END )AS buy_submit_name,
              a.buy_period_type,
              a.state,
              a.ins_date, -- 게시일
              a.reduce_request_price, -- 할인요청금액
              a.add_reduce_name, -- 추가할인명
              a.estimated_amount, -- 결제 예상금액
              a.first_area_idx, -- 시도 키값
              a.first_area_name, -- 시도 이름
              a.second_area_idx, -- 시군구 키값
              a.second_area_name, -- 시군구 이름
              a.outer_color_idx, -- 외장색상 키값
              a.inner_color_idx, -- 내장색상 키값
              a.outer_color, -- 외장색상
              a.outer_color_price, -- 외장색상 값
              a.inner_color, -- 내장색상
              a.inner_color_price, -- 내장색상값
              a.add_reduce_idx, -- 추가할인 키값
              a.add_reduce_name, -- 추가할인명
              a.release_service_idx, -- 출고서비스키값
              a.release_service_name, -- 출고서비스명
              a.end_date, -- 경매마감시간
              a.buy_period_type, -- 계약기간
              a.buy_bond_type, -- 공채구입
              a.buy_prepayment_rate, -- 선납금 요율
              a.buy_prepayment_price, -- 선납금 금액
              a.buy_deposit_rate, -- 보증금 요율
              a.buy_deposit_price, -- 보증금 금액
              a.buy_bank_idx, -- 금융사 키값
              a.buy_bank_name, -- 금융사
              a.buy_remains_rate, -- 잔존가치 요율
              a.buy_remains_price, -- 잔존가치 금액
              a.buy_mycar_type, -- 내차팔기
              a.buy_mycar_name, -- 차종
              a.buy_mycar_number, -- 차량번호
              a.buy_mycar_body_number, -- 차대번호
              a.buy_mycar_moving_distance, -- 주행거리
              a.buy_mycar_mission, -- 미션
              a.buy_mycar_option, -- 옵션
              a.buy_mycar_color, -- 색상
              a.buy_mycar_selling_area, -- 판매지역
              a.buy_mycar_buy_type, -- 구매형태
              a.buy_mycar_accident, -- 사고유무
              a.buy_mycar_img_1, -- 이미지1
              a.buy_mycar_img_2, -- 이미지2
              a.buy_mycar_img_3, -- 이미지3
              a.buy_mycar_img_4, -- 이미지4
              a.buy_mycar_img_5, -- 이미지5
              a.buy_mycar_img_6, -- 이미지6
              a.car_product_option_idx,
              a.car_product_option_name,
              a.car_product_option_price,
              b.car_product_idx,
              b.car_product_option,
              c.car_name, -- 차량 이름
              c.img_path, -- 차량 이미지
              c.car_price, -- 차량 금액
              c.point_yn, -- M포인트
              d.model_name, -- 모델 이름
              e.maker_name, -- 제조사 이름
              e.nation_type
            FROM
              tbl_estimate a JOIN tbl_car_product b ON a.car_product_idx = b.car_product_idx
              JOIN tbl_car c ON b.car_idx = c.car_idx AND c.use_yn = 'Y'
              JOIN tbl_car_model d ON c.car_model_idx = d.car_model_idx AND d.use_yn = 'Y'
              JOIN tbl_car_maker e ON d.car_maker_idx = e.car_maker_idx AND e.use_yn = 'Y'
            WHERE a.del_yn = 'N'
            AND a.state IN (1,3,4,5)
            AND a.estimate_idx = ?
            ";

    return $this->query_row($sql, array($estimate_idx));
  }

  //딜러가 견적정보를 볼 경우 조회 수 1 올림
  public function car_product_viws_up_in($data){
    $car_product_idx = $data['car_product_idx'];

    $this->db->trans_begin();

     $sql = "UPDATE
                tbl_car_product a
             SET
                views = a.views +1,
                upd_date = NOW()
              WHERE
                car_product_idx= ?
      ";

     $this->query($sql
                ,array(
                  $car_product_idx
                  )
                );

    if($this->db->trans_status() === FALSE){
      $this->db->trans_rollback();
      return "0";
    }else{
    $this->db->trans_commit();
      return "1";
    }
  }

  //선택 및 경매확정된 경매인지 아닌지 체크
  public function estimate_select_check($data) {
    $estimate_idx = $data['estimate_idx'];

    $sql = "SELECT
              b.dealer_idx,
              c.relieved_dealer_type
            FROM
              tbl_estimate a JOIN tbl_estimate_submit b ON a.estimate_idx = b.estimate_idx
              JOIN tbl_member c ON b.dealer_idx = c.member_idx
            WHERE
              a.estimate_idx = ?
            AND a.state IN (3,4,5)
            AND b.state IN (1,4)
            ";

    return $this->query_row($sql, array($estimate_idx));
  }

  //선택 및 경매확정된 경매인지 아닌지 체크
  public function dealer_select_count($data) {
    $estimate_idx = $data['estimate_idx'];

    $sql = "SELECT
              COUNT(1) cnt
            FROM
              tbl_estimate_submit a
            WHERE
              a.estimate_idx = ?
            AND a.state IN (1,4)
            ";

    return $this->query_cnt($sql, array($estimate_idx));
  }


} // 클래스의 끝
?>

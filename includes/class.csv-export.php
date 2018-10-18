<?php
add_action( 'init', 'create_csv_template' );
function create_csv_template() {
	//			 CSVで出力実行
	header( "Content-Type: text/csv; charset=shift_jis" );
	header( "Content-Disposition: filename=export.csv" );
	readfile('export.csv');

}

if ( ! class_exists( 'CsvExport' ) ) {

	class CsvExport {
//		public static $version = '0.0.0';

//		public static function init() {
//			add_action( 'init' , array( __CLASS__, 'export_csv'), 10, 2);
//		}

		// CSV 出力実行
		public static function export_csv( $ull ) {

			/*-------------------------------------------*/
			/*	CSVに出力する項目と順番
			/*-------------------------------------------*/

			$sort_data = array( 'ユーザー名', '日時', 'ステータス', 'IPアドレス', 'ユーザーエージェント', 'エラー' );

			$fp = fopen('php://output', 'wb');
//			foreach ($sort_data) {
				fputcsv($fp, $sort_data);
//			}

			readfile('export.csv');

			exit;


			// まずは配列に入っていたデータをCSV用に "" で囲んで格納
			foreach ( $sort_data as $key => $data ) {
				$c[] = '"' . $data . '"';
			}
			// 配列を . 区切りで格納する
			$csv[] = implode( ',', $c );

			/*-------------------------------------------*/
			/*	売掛金用のレコード出力
			/*-------------------------------------------*/
			foreach ( $ull as $key => $row ) {
				$role   = $row->user_login;
				$date   = date_i18n( "Y/n/j", strtotime( $row->activity_date ) );
				$status = $row->activity_status;
				$ip     = $row->activity_IP;
				$ua     = $row->activity_agent;
				$error  = $row->activity_errors;

				$c   = '';
				$c[] = '"' . $role . '"';            // 取引No
				$c[] = '"' . $date . '"';            // 取引日
				$c[] = '"' . $status . '"';            // 取引日
				$c[] = '"' . $ip . '"';            // 取引日
				$c[] = '"' . $ua . '"';            // 取引日
				$c[] = '"' . $error . '"';            // 取引日
				$c[] = '"売掛金"';                // 借方勘定科目
				$c[] = '""';                    // 借方補助科目
				$c[] = '"対象外"';                // 借方税区分
				$c[] = '""';                    // 借方部門
//				$c[] = '"' . $bill_total_add_tax . '"';    // 借方金額(円)
				$c[] = '""';                    // 借方税額
				$c[] = '"売上高"';                // 貸方勘定科目
				$c[] = '""';                    // 貸方補助科目
				$c[] = '"課売 8% 五種"';            // 貸方税区分
				$c[] = '""';                    // 貸方部門
//				$c[] = '"' . $bill_total_add_tax . '"';    // 貸方金額(円)
				$c[] = '""';                    // 貸方税額
//				$c[] = '"[ ' . esc_html( $client_name ) . ' ] ' . esc_html( $row->post_title ) . '"';    // 摘要
				$c[] = '""';                    // 仕訳メモ
				$c[] = '"BillVektor"';                    // タグ
				$c[] = '""';                    // MF仕訳タイプ
				$c[] = '""';                    // 決算整理仕訳
				$c[] = '"' . date( "Y/n/j H:i:s" ) . '"';    // 作成日時
				$c[] = '""';                    // 最終更新日時

				// 配列を , 区切りで格納
				$csv[] = implode( ',', $c );
			}

			$full_csv = implode( "\r\n", $csv );
			// CSVで出力実行


			header( "Content-Type: text/csv; charset=shift_jis" );
			$full_csv = mb_convert_encoding( $full_csv, "SJIS" );
			// header("Content-Type: text/csv; charset=utf-8");
			header( "Content-Disposition: filename=export.csv" );

			echo $full_csv;
			die();


		}
	}
}
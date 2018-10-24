<?php
if ( ! class_exists( 'CsvExport' ) ) {

	class CsvExport {

		// CSV 出力に含めるかどうか
		public static function is_export_csv_row( $logoin_row ) {
			$get_user_id     = ( ! empty( $_GET['user_id'] ) ) ? $_GET['user_id'] : '';
			$get_user_status = ( ! empty( $_GET['status'] ) ) ? $_GET['status'] : '';


			// ユーザーとステータスがある場合
			if ( $get_user_id && $get_user_id != -1 && $get_user_status ) {
				if ( $get_user_id == $logoin_row->user_id && $get_user_status == $logoin_row->activity_status ) {
					return true;
				}

				// ユーザー指定だけの時
			} elseif ( $get_user_id && $get_user_id != -1 ) {
				if ( $get_user_id == $logoin_row->user_id ) {
					return true;
				}

				// ステータス指定だけの時
			} elseif ( $get_user_status ) {
				if ( $get_user_status == $logoin_row->activity_status ) {
					return true;
				}

				// 絞り込み指定がない場合
			} else {
				return true;
			}

		}

		// CSV 出力実行
		public static function export_csv( $login_data, $date_start, $date_end ) {

			/*-------------------------------------------*/
			/*	過去に作成したexport.csvがあれば削除
			/*-------------------------------------------*/
			if ( file_exists( 'export.csv' ) ) {
				unlink( 'export.csv' );
			}

			/*-------------------------------------------*/
			/*	項目をCSVに書き込み
			/*-------------------------------------------*/
			$sort_data  = array( 'ユーザー名', '日時', 'ステータス', 'IPアドレス', 'ユーザーエージェント', 'エラー' );
			$fp         = fopen( 'export.csv', 'w' );
			$title_line = implode( ',', $sort_data );
			fwrite( $fp, $title_line . "\n" );

			/*-------------------------------------------*/
			/*	項目をCSVに書き込み
			/*-------------------------------------------*/

			foreach ( $login_data as $logoin_row ) {

				$id     = $logoin_row->user_id;
				$role   = $logoin_row->user_login;
				//ログインエラー時のユーザー名を取得
				if(is_null($role)){
					$role   =maybe_unserialize($logoin_row->activity_errors);
					$role = $role["user_login"];
				}
				$date   = date_i18n( 'Y/n/j', strtotime( $logoin_row->activity_date ) );
				$status = $logoin_row->activity_status;
				$ip     = $logoin_row->activity_IP;
				$ua     = $logoin_row->activity_agent;
				$error_raw  = $logoin_row->activity_errors;
				//非シリアル化
				$error = maybe_unserialize( $error_raw );
				//エラー時のキーを取得
				$error = key($error["errors"]);



				if ( self::is_export_csv_row( $logoin_row ) ) {
					$c   = [];
					$c[] = '"' . $role . '"';
					$c[] = '"' . $date . '"';
					$c[] = '"' . $status . '"';
					$c[] = '"' . $ip . '"';
					$c[] = '"' . $ua . '"';
					$c[] = '"' . $error . '"';

					$line = implode( ',', $c );
					fwrite( $fp, $line . "\n" );
				} // if ( is_export_csv_row( $logoin_row ) ) {
			} // foreach ( $login_data as $logoin_row ) {

			fclose( $fp );

			echo '<div><a href="export.csv" download class="button button-primary button-csv">CSVをダウンロード</a></div>';

		}
	}
}

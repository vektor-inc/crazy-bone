<?php
if ( ! class_exists( 'CsvExport' ) ) {

	class CsvExport {

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

				$role   = $logoin_row->user_login;
				$date   = date_i18n( 'Y/n/j', strtotime( $logoin_row->activity_date ) );
				$status = $logoin_row->activity_status;
				$ip     = $logoin_row->activity_IP;
				$ua     = $logoin_row->activity_agent;
				$error  = $logoin_row->activity_errors;

				$c   = [];
				$c[] = '"' . $role . '"';
				$c[] = '"' . $date . '"';
				$c[] = '"' . $status . '"';
				$c[] = '"' . $ip . '"';
				$c[] = '"' . $ua . '"';
				$c[] = '"' . $error . '"';

				$line = implode( ',', $c );
				fwrite( $fp, $line . "\n" );
			}

			fclose( $fp );

			echo '<div><a href="export.csv" download>CSVをダウンロード</a></div>';

		}
	}
}

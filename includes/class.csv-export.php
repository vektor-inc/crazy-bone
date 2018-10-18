<?php
if ( ! class_exists( 'CsvExport' ) ) {

	class CsvExport {

		// CSV 出力実行
		public static function export_csv( $ull, $date_start, $date_end ) {

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
			foreach ( $ull as $row ) {

				//比較のためにunixタイムに変換
				$unix_date = strtotime( $row->activity_date );
				$unix_date_start = strtotime( $date_start );
				//日付+23時間59分59秒を追加
				$unix_date_end = strtotime( $date_end ) + 86399;


				if(!empty($unix_date_start) || !empty($unix_date_end)){

					//開始日が終了日より遅い場合は警告
					if($unix_date_start >=  $unix_date_end){
						echo "開始日が、終了日より遅いです。";
						return;
					}

					//指定した期間の日付はスキップ
					if($unix_date < $unix_date_start || $unix_date > $unix_date_end ){
						continue;
					}

				}


				$role   = $row->user_login;
				$date   = date_i18n( "Y/n/j", strtotime( $row->activity_date ) );
				$status = $row->activity_status;
				$ip     = $row->activity_IP;
				$ua     = $row->activity_agent;
				$error  = $row->activity_errors;

				$c   = '';
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
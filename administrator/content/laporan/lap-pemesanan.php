<?php
require '../../app/conn.php';
require '../../app/func.inc.php';
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../../assets/css/jquery-ui.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.css">
	<script src="../../assets/js/jquery-1.11.1.js"></script>
	<script src="../../assets/js/jquery-ui.js"></script>
	<script>
		$(function() {
			$( "#tgl1" ).datepicker({
				showAnim: "drop",
				dateFormat: "yy-mm-dd",
			});
		});
		$(function() {
			$( "#tgl2" ).datepicker({
            showAnim: "drop",
            dateFormat: "yy-mm-dd",
            //minDate: "-0D",
            beforeShow: function () {
                var a = jQuery("#tgl1").datepicker('getDate');
                if (a) return {
                    minDate: a
                }
            }
        	});
		});
	</script>
</head>
<body>
    <div class="container-fluid">
    	<div class="row" style="margin-top:10px;">
        	<div class="col-md-12">
      			<form method="post" action="" class="form-inline">
                    <fieldset>
                    <legend>Cetak Laporan Pemesanan</legend>
                    <div class="form-group">
                        <label>Lihat dari tanggal </label>
                        <input type="text" id="tgl1" name="tgl1" class="form-control" placeholder="thn-bln-tgl" required>
                    </div>
                    <div class="form-group">
                        <label>sampai </label>
                        <input type="text" id="tgl2" name="tgl2" class="form-control" placeholder="thn-bln-tgl" required>
                    </div>
                        <input type="submit" name="pemesanan" value="Proses" class="btn btn-primary">
                    </fieldset>
                </form>
                <hr>      	
            </div>
        </div>
        <div class="row" style="margin-top:10px;">
        	<div class="col-md-12">
                    <?php
					if (isset($_POST['pemesanan'])) {
						$qtampil = mysql_query("SELECT trans_pemesanan.*,suplier.*,barang.*, jumlah_pesan * harga_beli AS subtotal FROM trans_pemesanan, suplier, barang WHERE trans_pemesanan.id_suplier=suplier.id_suplier AND trans_pemesanan.kd_barang=barang.kd_barang AND tgl BETWEEN '$_POST[tgl1]' AND '$_POST[tgl2]'") or die (mysql_error());
						$no=1;
					?>
                    <form method="post" action="cetak.php">
                    	<input type="hidden" name="tgl1" value="<?php echo $_POST['tgl1'];?>">
                        <input type="hidden" name="tgl2" value="<?php echo $_POST['tgl2'];?>">
                        <button type="submit" name="cetak_pembelian" class="btn btn-primary"><span class="glyphicon glyphicon-print"></span> Cetak Laporan</button>
                    </form>
                    <table class="table table-responsive table-bordered" style="margin-top:5px;">
                    <thead>
                    	<tr>
                        	<th colspan="8" class="text-center bg-primary">
                            	<h3>Data Transaksi Pemesanan<br>
                            	Periode <?php echo $_POST['tgl1'];?> s/d <?php echo $_POST['tgl2'];?></h3>
                            </th>
                        </tr>
                        <tr>
                        	<th>No</th>
                            <th>Suplier</th>
                            <th>Kode Barang</th>
                            <th>Barang</th>
                            <th>Jumlah Beli</th>
                            <th>Harga Beli Per Barang</th>
                            <th>Subtotal</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php

						while ($rtampil = mysql_fetch_assoc($qtampil)) {
					?>
						<tr>
                        	<td><?php echo $no;?></td>
                            <td><?php echo $rtampil['nama_suplier'];?></td>
                            <td><?php echo $rtampil['kd_barang'];?></td>
                            <td><?php echo $rtampil['nama'];?></td>
                            <td><?php echo $rtampil['jumlah_pesan'];?></td>
                            <td>Rp. <?php echo rupiah($rtampil['harga_beli']); ?></td>
                            <td>Rp. <?php echo rupiah($rtampil['subtotal']);?></td>
                            <td><?php echo $rtampil['tgl'];?></td>
                        </tr>
					<?php
						$no++;		
						}
						$qjml = mysql_query("SELECT SUM(jumlah_pesan) AS total FROM trans_pemesanan WHERE tgl BETWEEN '$_POST[tgl1]' AND '$_POST[tgl2]'");
						$rjml = mysql_fetch_assoc($qjml);

                        $qtotal = mysql_query("SELECT SUM(jumlah_pesan * harga_beli) AS tot_harga FROM trans_pemesanan WHERE tgl BETWEEN '$_POST[tgl1]' AND '$_POST[tgl2]'");
                        $rtotal = mysql_fetch_assoc($qtotal);	
					?>
                    	<tr>
                        	<td colspan="4" class="text-right"><strong>Jumlah Barang</strong></td>
                            <td><?php echo $rjml['total'];?></td>
                            <td class="text-right"><strong>Jumlah Total</strong></td>
                            <td>Rp. <?php echo rupiah($rtotal['tot_harga']); ?></td>
                        </tr>
                    </tbody>
                	</table>
                    <?php
					}
					?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
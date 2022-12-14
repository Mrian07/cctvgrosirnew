<div style="">
	<a class="float-right btn btn-primary" href="<?=site_url("master/sliderform")?>"><i class="fas fa-plus-circle"></i> Tambah Promo</a>
	<h4 class="page-title">Promo Slider</h4>
</div>
<div class="m-lr-0">
	<div class="card">
		<div class="card-body table-responsive">
			<table class="table mt-3">
				<tr>
					<th scope="col">#</th>
					<th scope="col">Judul</th>
					<th scope="col">Tipe</th>
					<th scope="col">Status</th>
					<th scope="col">Masa Promo</th>
					<th scope="col">Aksi</th>
				</tr>
		<?php
			$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
			$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
			$perpage = 10;

			$rows = $this->db->get("promo");
			$rows = $rows->num_rows();

			$this->db->from('promo');
			$this->db->order_by($orderby,"desc");
			$this->db->limit($perpage,($page-1)*$perpage);
			$pro = $this->db->get();
			
			if($rows > 0){
				$no = 1;
			foreach($pro->result() as $r){
				$mulai = $this->func->ubahTgl("YmdHis",$r->tgl);
				$selesai = $this->func->ubahTgl("YmdHis",$r->tgl_selesai);
				$tipe = ($r->jenis == 1) ? "<b class='text-success'>SLIDER</b>" : "<b class='text-danger'>IKLAN</b>";
				$tipe = ($r->jenis == 3) ? "<b class='text-primary'>SLIDER MOBILE</b>" : $tipe;
				$status = ($r->status == 1 AND $mulai <= date("YmdHis") AND $selesai >= date("YmdHis")) ? "<span class='badge badge-success'>AKTIF</span>" : "<span class='badge badge-danger'>NONAKTIF</span>";
		?>
			<tr>
				<td class="text-center"><img style="max-height:70px;max-width:120px;" src="<?=base_url("promo/".$r->gambar)?>" /></td>
				<td><?=strtoupper(strtolower($r->caption))?></td>
				<td><?=$tipe?></td>
				<td><?=$status?></td>
				<td><b class="text-muted"><?="Mulai: </b>".$this->func->ubahTgl("d/m/Y H:i",$r->tgl)."<br/><b class=\"text-muted\">Selesai:</b> ".$this->func->ubahTgl("d/m/Y H:i",$r->tgl_selesai)?></td>
				<td>
					<a href="<?php echo site_url("master/sliderform/".$r->id); ?>" class="btn btn-primary"><i class="fas fa-pencil-alt"></i> Edit</a>
					<a href="javascript:hapusPromo(<?php echo $r->id; ?>)" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Hapus</a>
				</td>
			</tr>
		<?php	
				$no++;
			}
			}else{
				echo "<tr><td colspan=5>Belum ada promo</td></tr>";
			}
		?>
		</table>

		<?=$this->func->createPagination($rows,$page,$perpage);?>

		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		
	});
	
	function hapusPromo(pro){
		<?php if($this->func->demo()){ ?>
			swal.fire("Fitur dibatasi","Mohon maaf, tidak dapat menghapus promo saat mode demo diaktifkan","error");
		<?php }else{ ?>
			swal.fire({
				title: "Anda yakin menghapus?",
				text: "promo yang sudah dihapus tidak dapat dikembalikan",
				type: "warning",
				showCancelButton: true,
				cancelButtonColor: '#d33',
				cancelButtonText: "Batal",
				confirmButtonText: "Tetap Hapus"
			}).then((result)=>{
				if(result.value){
					$.post("<?php echo site_url('master/hapusslider'); ?>",{"pro":pro,[$("#names").val()]:$("#tokens").val()},function(msg){
						var data = eval("("+msg+")");
						updateToken(data.token);
						if(data.success == true){
							swal.fire("Berhasil!","Berhasil menghapus data","success").then((data) =>{
								location.reload();
							});
						}else{
							swal.fire("Gagal!","Gagal menghapus data, terjadi kesalahan sistem","error");
						}
					});
				}
			});
		<?php } ?>
	}
	
	function refreshTabel(page){
		window.location.href = "<?php echo site_url('master/slider/?page='); ?>"+page;
	}
</script>
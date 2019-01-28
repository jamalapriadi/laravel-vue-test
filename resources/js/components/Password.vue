<template>
    <div class="card card-default">
		<div class="card-header">
			<h6 class="card-title">Change Password</h6>
		</div>
		<div class="card-body">
			<form  id="form" onsubmit="return false;" class="form-horizontal">
				<div id="pesan"></div>
				<div class="form-group">
					<label for="" class="control-label text-semibold">Password Lama</label>
					<input type="password" name="current" class="form-control">
				</div>
				<div class="form-group">
					<label for="" class="control-label text-semibold">Password Baru</label>
					<input type="password" name="password" class="form-control">
				</div>
				<div class="form-group">
					<label for="" class="control-label text-semibold">Konfirmasi Password</label>
					<input type="password" name="password_confirmation" class="form-control">
				</div>
				<div class="form-group">
					<button class="btn btn-primary">
						<i class="icon-floppy-disk"></i> Change Password
					</button>
				</div>
			</form>
		</div>
	</div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
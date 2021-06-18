<template>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>



	<div class="container h-100">
			<!--img :src="`/img/icon/logos.png`" style="width: 130px; margin-left:200px ;"-->
		<div class="d-flex justify-content-center h-100">

			<div class="user_card">

				<div class="d-flex justify-content-center">
					<div class="brand_logo_container">
						<img src="/img/icon/logo1.png" class="brand_logo" alt="Logo">
					</div>
				</div>
				<div class="d-flex justify-content-center form_container">
					<form @submit.prevent="resetpass">
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-user"></i></span>
							</div>
							<input type="email"  v-model="form.email" class="form-control input_user" value="" placeholder="Email":class="{ 'is-invalid': form.errors.has('email') }">
      <has-error :form="form" field="email"></has-error>

                    	</div>

						<div class="form-group">
							<div class="custom-control custom-checkbox">

							</div>
						</div>
							<div class="d-flex justify-content-center mt-3 login_container">
				 	<button type="submit" name="button" class="btn login_btn">Reset</button>
				   </div>
					</form>
				</div>

				<div class="mt-4">
					<div class="d-flex justify-content-center links">
					<router-link to="/login" style="text-decoration:none;">	Login In And Join The Team </router-link>

					</div>


					<div class="d-flex justify-content-center links  error" v-if="authError">
                       {{ authError }}
					</div>

			</div>
				</div>


		</div>


	</div>
<footer>


  <strong style="float:left ; margin-top:22px; margin-left:529px; " > Copyright  &copy; 2020 <a href="https://manarate.com">Manarate</a>. All rights reserved. </strong>

</footer>
	</body>
</html>

</template>
<script>

 import {login} from '../helpers/auth';
export default {
        name: "login",
        data() {
            return {
                form: new Form ({
                    email: '',
                }),
                error: null
            };
        },
        methods: {
          resetpass(){
			   let axiosConfig = {
                    headers: {
                    "Authorization": 'Bearer '
                    },
			   }
			  
          this.form.post('api/auth/reset-password').then(function(data){
               if (data.data.action === "email send successfully"){  
				   //this.form.reset(); 
				/* Toast.fire({
                        icon: 'success',
                        title: 'Check you email'
						})*/
					//router.push("login");
					//this.$routes.push("not-found");
					//this.$router.push({path: '/login'})
					window.location.href = 'http://stark-beyond-68322.herokuapp.com/login';
						
			   }
			   else{
                 seww.fire(
					'Email invalid',
                    );
			   }
			   
                }).catch(()=>{
					//console.log(err);
					
                });

          }
         },
        computed: {
            authError() {
                return this.$store.getters.authError;
            }
        }
}
</script>
<style scoped>
.error {
    text-align: center;
    color: red;

}
	/* Coded with love by Mutiullah Samim */

		.user_card {
			height: 400px;
			width: 350px;
			margin-top: 150px;;

			background:#88C1E7;
			position: relative;
			display: flex;
			justify-content: center;
			flex-direction: column;
			padding: 10px;
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			-webkit-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			-moz-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			border-radius: 5px;

		}
		.brand_logo_container {
			position: absolute;
			height: 170px;
			width: 170px;
			top: -75px;
			border-radius: 50%;
			background: #60a3bc;
			padding: 10px;
			text-align: center;
		}
		.brand_logo {
			height: 150px;
			width: 150px;
			border-radius: 50%;
			border: 2px solid white;
		}
		.form_container {
			margin-top: 100px;
		}
		.login_btn {
			width: 100%;
			background: #092534 !important;
			color: white !important;
		}
		.login_btn:focus {
			box-shadow: none !important;
			outline: 0px !important;
		}
		.login_container {
			padding: 0 2rem;
		}
		.input-group-text {
			background: #092534 !important;
			color: white !important;
			border: 0 !important;
			border-radius: 0.25rem 0 0 0.25rem !important;
		}
		.input_user,
		.input_pass:focus {
			box-shadow: none !important;
			outline: 0px !important;
		}
		.custom-checkbox .custom-control-input:checked~.custom-control-label::before {
			background-color: #c0392b !important;
		}
		footer{
			margin-left: auto;
            margin-right: auto;}

</style>

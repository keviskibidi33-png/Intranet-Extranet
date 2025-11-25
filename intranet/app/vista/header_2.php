<header id="header" class="clean">

    <div class="">

        <div class="container">

            <div class="row">

                

                <div class="col-lg-12 col-md-12 col-sm-12">

                    <div class="topbar-center" style="text-align: left; padding: 20px 0;"> <br>

                      <a href="https://geofal.com.pe/2025" style="display: inline-block;">
                        <img src="<?= ruta ?>admin/publico/img/cabercera.png" alt="Geofal" style="width: 185px; height: 110px; object-fit: contain;">
                      </a>

                      <span>&nbsp;&nbsp;</span>

                        <?php if (isset($_SESSION['id_geo']) && !empty($_SESSION['id_geo'])) { ?>

                          <a style="float: right;

    background: #003b49;

    padding: 10px 19px;

    border-radius: 6px;

    color: #ffffff;" href="<?= ruta ?>salir"><i class="fa fa-sign-out" ></i>&nbsp;Cerrar Sesión </a>

                        <?php }  ?>

                    </div>

                </div>

            </div>

        </div>

    </div>



</header>

 



 
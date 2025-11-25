<section class="page-section-pt pb-50" style="background-color: #ddd9d7; color: #ffffff;">
    <div class="container">
      <div class="row">
        <div class="section-title text-center">
          <h2 class="title" style="color: #b7380a;">Contáctanos</h2>
        </div>
        <div class="col-sm-12 pb-20">
          <div class="defoult-form form-2">
            <form role="form" method="post" action="pro_correo.php">
              <div class="form-group half-group">
                <div class="input-group">
                  <input type="text" placeholder="Nombre y Apellido" class="form-control" name="nom" required>
                </div> <br>
                <div class="input-group">
                  <input type="email" placeholder="Correo Electrónico" class="form-control" name="cor" required>
                </div> <br>
                <div class="input-group">
                  <input type="text" placeholder="N° de Celular" class="form-control" name="tel" required>
                </div>
              </div>
              <div class="form-group half-group">
                <div class="input-group">
                  <textarea class="form-control input-message" placeholder="Deseo información sobre:" rows="5" name="msj" required></textarea>
                </div>
              </div>
              <button name="submit" type="submit" value="Send" class="button border5 animated middle-fill"><span>Enviar</span>
              </button>

              <input type="hidden" name="google-response-token" id="google-response-token">

            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
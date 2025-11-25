<footer class="footer dark-bg pb-0 text-center footer-scroll" style="margin-top: 100px; padding-top: 0;">

  

  <a href="https://geofal.com.pe/contactanos/"> <img src="<?= ruta ?>admin/publico/img/footer.jpeg" class="footer-image" alt=""> </a>

  

    

</footer>



<style>

  .footer {

    width: 100%;

    height: auto; 

    padding: 0;

  }



  .footer-image {

    width: 100%;

    height: auto;

    display: block;

    object-fit: cover;

  }

  /* Footer oculto inicialmente, aparece al hacer scroll */
  .footer-scroll {
    opacity: 0 !important;
    visibility: hidden !important;
    transform: translateY(20px);
    transition: opacity 0.5s ease, transform 0.5s ease, visibility 0.5s ease;
  }

  .footer-scroll.visible {
    opacity: 1 !important;
    visibility: visible !important;
    transform: translateY(0);
  }





  /* .footer {

    position: fixed;

    bottom: 0;

    left: 0;

    width: 100%;

    background-color: #000;

    padding: 0;

}



.footer-image {

    width: 100%;

    height: auto;

    display: block;

    object-fit: cover;

} */

</style>







<div id="back-to-top"><a class="top arrow" href="#top"><i class="fa fa-chevron-up"></i></a></div>



<script type="text/javascript" src="<?= ruta ?>include/js/jquery.min.js"></script>





<script type="text/javascript" src="<?= ruta ?>app/js/funciones.js"></script>



<script type="text/javascript" src="<?= ruta ?>include/js/bootstrap.min.js"></script>

<script type="text/javascript" src="<?= ruta ?>include/js/jquery.appear.js"></script>

<script type="text/javascript" src="<?= ruta ?>include/js/mega-menu/mega_menu.js"></script>

<script type="text/javascript" src="<?= ruta ?>include/js/owl-carousel/owl.carousel.min.js"></script>

<script type="text/javascript" src="<?= ruta ?>include/js/counter/jquery.countTo.js"></script>

<script type="text/javascript" src="<?= ruta ?>include/js/skills-graph/jqbar.js"></script>

<script type="text/javascript" src="<?= ruta ?>include/revolution/js/jquery.themepunch.tools.min.js"></script>

<script type="text/javascript" src="<?= ruta ?>include/revolution/js/jquery.themepunch.revolution.min.js"></script>

<script type="text/javascript" src="<?= ruta ?>include/revolution/js/extensions/revolution.extension.slideanims.min.js"></script>

<script type="text/javascript" src="<?= ruta ?>include/revolution/js/extensions/revolution.extension.layeranimation.min.js"></script>

<script type="text/javascript" src="<?= ruta ?>include/revolution/js/extensions/revolution.extension.navigation.min.js"></script>

<script type="text/javascript" src="<?= ruta ?>include/revolution/js/extensions/revolution.extension.parallax.min.js"></script>

<script type="text/javascript" src="<?= ruta ?>include/revolution/js/extensions/revolution.extension.kenburn.min.js"></script>



<script type="text/javascript" src="<?= ruta ?>include/js/magnific-popup/jquery.magnific-popup.min.js"></script>



<script type="text/javascript" src="<?= ruta ?>include/js/style-customizer.js"></script>

<script type="text/javascript" src="<?= ruta ?>include/js/custom.js"></script>







<script>

  //////////////////////////////////////////////////////

  function login_2(ruta, e) {



    e.preventDefault();

    var dat = $("#for_login").serialize();



    $.ajax({

      type: "POST",

      url: ruta + "?pagina=login",

      data: dat + "&login=ok",

      dataType: "json",

      success: function(data) {

        //alert(JSON.stringify(data, null, 2));

        if (data.es == 1) {



          let timerInterval

          Swal.fire({

            title: "Accediendo",

            html: "Cargando contenidos",

            timer: 2000,

            timerProgressBar: true,

            didOpen: () => {

              Swal.showLoading()

              // Verificar que el elemento existe antes de actualizarlo
              const b = Swal.getHtmlContainer().querySelector('b')

              if (b) {
                timerInterval = setInterval(() => {

                  b.textContent = Swal.getTimerLeft()

                }, 100)
              }

            },

            willClose: () => {

              clearInterval(timerInterval)

            }

          }).then((result) => {

            // Read more about handling dismissals below 

            if (result.dismiss === Swal.DismissReason.timer) {

              console.log('I was closed by the timer')



              top.document.location.href = "<?= ruta ?>ordenes";



            }

          })









   







        }

        if (data.es == 2) {

          Swal.fire({

            title: "Alerta",

            text: "Usuario o clave inválida",

            icon: "warning",

          });

        }

      },

    });

  }

</script>







<!-- WhatsHelp.io widget -->

<script type="text/javascript">

  (function() {

    var options = {

      facebook: "geofalperu", // Facebook page ID

      whatsapp: "+51993077479", // WhatsApp number

      call_to_action: "Escríbenos", // Call to action

      button_color: "#129BF4", // Color of button

      position: "left", // Position may be 'right' or 'left'

      order: "facebook,whatsapp", // Order of buttons

    };

    var proto = document.location.protocol,

      host = "whatshelp.io",

      url = proto + "//static." + host;

    var s = document.createElement('script');

    s.type = 'text/javascript';

    s.async = true;

    s.src = url + '/widget-send-button/js/init.js';

    s.onload = function() {

      WhWidgetSendButton.init(host, proto, options);

    };

    var x = document.getElementsByTagName('script')[0];

    x.parentNode.insertBefore(s, x);

  })();

</script>

<!-- /WhatsHelp.io widget -->

<script>
  // Mostrar footer SOLO cuando el usuario hace scroll hacia abajo
  (function() {
    var footer = document.querySelector('.footer-scroll');
    if (!footer) return;

    var isVisible = false;
    var scrollThreshold = 100; // Píxeles de scroll antes de mostrar el footer
    var hasScrolledOnce = false; // Flag para saber si el usuario ha hecho scroll

    function checkScroll() {
      var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      
      // Solo mostrar si el usuario ha hecho scroll hacia abajo
      if (scrollTop > scrollThreshold) {
        hasScrolledOnce = true;
        if (!isVisible) {
          footer.classList.add('visible');
          isVisible = true;
        }
      }
    }

    // NO verificar al cargar - el footer debe estar oculto inicialmente
    // Solo verificar cuando el usuario hace scroll
    window.addEventListener('scroll', function() {
      checkScroll();
    }, { passive: true });
    
    // También verificar con resize (por si cambia el tamaño de la ventana)
    window.addEventListener('resize', function() {
      if (hasScrolledOnce) {
        checkScroll();
      }
    });
  })();
</script>
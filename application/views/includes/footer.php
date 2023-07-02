	</div><!-- ./wrapper -->

	<!-- REQUIRED JS SCRIPTS -->
    <!-- jQuery 2.1.3 -->
    <!--<script src="<?php echo base_url(); ?>assets/plugins/jQuery/jQuery-2.1.3.min.js"></script>-->
    <!-- Bootstrap 3.3.2 JS -->
    <!-- <script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> -->
    <!-- InputMask -->
    <!-- <script src="<?php echo base_url(); ?>assets/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script> -->
    <!-- AdminLTE App -->
    <!-- <script src="<?php echo base_url(); ?>assets/dist/js/app.min.js" type="text/javascript"></script> -->
    <!-- date-range-picker -->
    <!-- <script src="<?php echo base_url(); ?>assets/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script> -->

    <!-- BEGIN CORE JS FRAMEWORK--> 
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-1.8.3.min.js" type="text/javascript"></script> 
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script> 
    <script src="<?php echo base_url(); ?>assets/plugins/boostrapv3/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/breakpoints.js" type="text/javascript"></script> 
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script> 
    <!-- END CORE JS FRAMEWORK --> 
    <!-- BEGIN PAGE LEVEL JS -->  
    <script src="<?php echo base_url(); ?>assets/plugins/pace/pace.min.js" type="text/javascript"></script>  
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-block-ui/jqueryblockui.js" type="text/javascript"></script> 
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-slider/jquery.sidr.min.js" type="text/javascript"></script>  
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-sparkline/jquery-sparkline.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-numberAnimate/jquery.animateNumbers.js" type="text/javascript"></script> 
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script> 
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-inputmask/jquery.inputmask.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-autonumeric/autoNumeric.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/ios-switch/ios7-switch.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-select2/select2.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/dropzone/dropzone.min.js" type="text/javascript"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/morris/morris.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/plugins/jquery-datatable/js/jquery.dataTables.min.js" type="text/javascript" ></script>
    <script src="<?php echo base_url();?>assets/plugins/jquery-datatable/extra/js/TableTools.min.js" type="text/javascript" ></script>
    <script src="<?php echo base_url();?>assets/plugins/datatables-responsive/js/datatables.responsive.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/plugins/datatables-responsive/js/lodash.min.js" type="text/javascript"></script>

    <!-- END PAGE LEVEL PLUGINS -->
    <script src="<?php echo base_url(); ?>assets/js/form_elements.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/admin.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/prayer.js"></script>
    <script type="text/javascript">
      var base_url = "<?php echo base_url();?>";
      var centreGot = false;
      $(function () {
        if ($('#line-chart').length) {
          var line = new Morris.Line({
            element: 'line-chart',
            resize: true,
            data: [
            { y: '2015-04-01', a: 50, b: 40 },
            { y: '2015-04-02', a: 65,  b: 55 },
            { y: '2015-04-03', a: 50,  b: 40 },
            { y: '2015-04-05', a: 75,  b: 65 },
            { y: '2015-04-08', a: 50,  b: 40 },
            { y: '2015-04-11', a: 75,  b: 65 },
            { y: '2015-04-12', a: 100, b: 90 }
            ],
            xkey: 'y',
            ykeys: ['a'],
            labels: ['Sold'],
            lineColors:['#0aa699','#d1dade'],
          });
        }
      });
    </script>
    <?php if (isset($map)) echo $map['js']; ?>
    <!-- BEGIN CORE TEMPLATE JS -->
    <script src="<?php echo base_url(); ?>assets/js/isolangs.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/core.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/chat.js" type="text/javascript"></script> 
    <script src="<?php echo base_url(); ?>assets/js/demo.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/datatables.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/sentences.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/videos.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/questions.js" type="text/javascript"></script>
  <!-- END CORE TEMPLATE JS -->
</body>
</html>

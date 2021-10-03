<?php if (!empty($this->session->flashdata('error'))) { ?>
    <script>
      let error = "<?php echo $this->session->flashdata('error'); ?>";
      Notiflix.Notify.Failure(error);
    </script>
  <?php } if (!empty($this->session->flashdata('success'))) { ?>
    <script>
      let success = "<?php echo $this->session->flashdata('success'); ?>";
      Notiflix.Notify.Success(success);
    </script>
  <?php } ?>

</body>
</html>
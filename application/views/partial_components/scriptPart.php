<script src="<?= base_url('assets/library/bootstrap-5.2.1/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/library/jquery-3.6.1.min.js') ?>"></script>
<script src="<?= base_url('assets/library/notiflix-aio-3.2.5.min.js') ?>"></script>
<script src="<?= base_url('assets/library/datatables-1.13.4/datatables.min.js') ?>"></script>
<script>
    Notiflix.Notify.init({
        showOnlyTheLastOne: true,
        fontSize: '14px',
        position: 'center-top',
        cssAnimationStyle: 'from-top'
    });

    Notiflix.Report.init({
        borderRadius: '0px',
    });

    Notiflix.Loading.init({
        svgColor: '#dc3545',
        backgroundColor: 'rgba(255,255,255,0.8)'
    });

    Notiflix.Confirm.init({
        borderRadius: '0px',
        okButtonBackground: '#dc3545',
        titleColor: '#dc3545',
    });
</script>
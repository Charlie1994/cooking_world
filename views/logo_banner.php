<!-- logo starts -->
<div class="container-fluid logo-header">
    <div class="row">
        <div class="col-md-2">
            <a href="../app/views/home/homepage.html.twig">
                <img class="img-responsive" src="../resource/images/layout/logo.png">
            </a>
        </div>
        <div class="col-sm-6 col-md-6 col-md-offset-4">
            <form class="form-inline home-search-box" action="#">
                <div class="input-group pull-right">
                    <input type="text" class="form-control" placeholder="Search for recipes" id="logo-search-box">
                    <span class="input-group-btn">
                        <button class="btn btn-danger" type="button" id="logo-search">
                            <span class="glyphicon glyphicon-search"></span>
                        </button>
                    </span>
                </div>
            </form>
        </div>
    </div>

</div><hr>
<script>
    $(function () {
        $('#logo-search').click(function () {
            window.location.href = 'search_recipe.php?pagenum=1&keyword='+$('#logo-search-box').val();
        });
    });
</script>
<!-- logo ends -->
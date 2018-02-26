function Ingredients($ingredients) {
    Ingredients.prototype.$ingredients = $ingredients;
    Ingredients.prototype.bindAddItemEvent = function ()
    {
        var $ingredients = this.$ingredients,
            $ingredientItems = $ingredients.find('.ingre-items'),
            ingredientItem = '<div class="row ingre-item"><div class="form-group"><div class="col-sm-4"><input title="ingredient-quantity" type="text" class="form-control"></div><div class="col-sm-6 col-sm-offset-1"><input title="ingredient-name" type="text" class="form-control"></div><div class="col-sm-1"><div class="add-btn"><i class="fa fa-plus" aria-hidden="true"></i></div></div></div></div>',
            refreshIngreAddBtn = function ()
            {
                $ingredients.find('.add-btn')
                    .bind('click', function () {
                        $(this).remove();
                        $ingredientItems.append(ingredientItem);
                        refreshIngreAddBtn();
                    })
                    .each(function ()
                    {
                        adjustAddBtn();
                    });
            };
        refreshIngreAddBtn();
    }
}
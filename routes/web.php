<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    RecipeController,
    CommentController,
    ProfileController,
    CategoryController,
    AdminController,
    UserController,
    AboutController,
    CatalogController,
    IngredientController,
    NotificationController
};
use App\Http\Middleware\AdminMiddleware;

// Главная страница
Route::get('/', [HomeController::class, 'index'])->name('home.index');

// Управление рецептами пользователем
Route::resource('recipes', RecipeController::class);
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
Route::get('/recipes/{id}', [RecipeController::class, 'show'])->name('recipes.show');
Route::post('/recipes/{id}/favorite', [RecipeController::class, 'addToFavorites'])->name('recipes.addToFavorites')->middleware('auth');
Route::delete('/recipes/{id}/favorite', [RecipeController::class, 'removeFromFavorites'])->name('recipes.removeFromFavorites')->middleware('auth');

// Административные маршруты для рецептов
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::put('/admin/recipes/{recipe}/approve', [RecipeController::class, 'approve'])->name('admin.recipes.approve');
    Route::put('/admin/recipes/{recipe}/reject', [RecipeController::class, 'reject'])->name('admin.recipes.reject');
    Route::get('/admin/recipes', [AdminController::class, 'manageRecipes'])->name('admin.recipes.index');
});

// Управление комментариямиё
Route::post('/recipes/{recipe}/comments', [CommentController::class, 'store'])->name('comments.store')->middleware('auth');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy')->middleware('auth');
Route::get('/comments', [CommentController::class, 'index'])->name('admin.comments.index')->middleware('auth');
Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit')->middleware('auth');
Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update')->middleware('auth');

// Дашборд и страницы
Route::get('/dashboard', [RecipeController::class, 'index'])->name('dashboard');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');

// Показ категорий
Route::post('/categories', [CategoryController::class, 'index'])->name('categories.store');

// Маршруты профиля
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/notifications', [NotificationController::class, 'index'])
        ->name('profile.notifications');
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.markAllAsRead');
    Route::put('/ingredients/{ingredient}', [IngredientController::class, 'update'])->name('ingredients.update');
    Route::delete('/ingredients/{ingredient}', [IngredientController::class, 'destroy'])->name('ingredients.destroy');
    Route::post('/admin/ingredients', [IngredientController::class, 'store'])->name('ingredients.store');
    Route::get('/recipes/{recipe}/edit', [IngredientController::class, 'edit'])->name('recipes.edit');
    Route::put('/recipes/{recipe}', [IngredientController::class, 'updateRecipe'])->name('recipes.update');
    Route::delete('/recipes/{recipe}', [IngredientController::class, 'destroyRecipe'])->name('recipes.destroy');
    Route::post('/recipes/{recipe}/steps', [IngredientController::class, 'addStep'])->name('steps.store');
    Route::put('/steps/{step}', [IngredientController::class, 'updateStep'])->name('steps.update');
    Route::delete('/steps/{step}', [IngredientController::class, 'destroyStep'])->name('steps.destroy');
    Route::get('/profile/recipes', [RecipeController::class, 'userRecipes'])->name('profile.recipes');
    Route::get('/profile/ingredients', [IngredientController::class, 'index'])->name('profile.ingredients.index');
});

// Админ-панель
// Управление рецептами
Route::get('/admin/recipes', [AdminController::class, 'manageRecipes'])->name('admin.recipes.index')->middleware('auth');
Route::post('/admin/recipes/{recipe}/approve', [RecipeController::class, 'approve'])->name('admin.recipes.approve')->middleware('auth');
Route::post('/admin/recipes/{recipe}/reject', [RecipeController::class, 'reject'])->name('admin.recipes.reject')->middleware('auth');
// Управление пользователями
Route::middleware('auth')->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users/{user}/update-role', [UserController::class, 'updateRole'])->name('admin.users.updateRole');
    Route::post('/admin/users/{user}/ban', [UserController::class, 'ban'])->name('admin.users.ban');
});

// Управление категориями
Route::middleware(['auth'])->group(function () {
    // Админ панель
    Route::get('/admin', [AdminController::class, 'index'])
        ->name('admin.index');

    // Маршруты для категорий
    Route::prefix('admin/categories')->name('admin.categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])
            ->name('index');
        Route::post('/', [CategoryController::class, 'store'])
            ->name('store');
        Route::put('/{category}', [CategoryController::class, 'update'])
            ->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])
            ->name('destroy');
    });
});

// Пользовательские маршруты для рецептов
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/history', [ProfileController::class, 'history'])->name('profile.history');
    Route::post('/recipes/clear-history', [ProfileController::class, 'clearHistory'])->name('recipes.clearHistory');
    Route::get('/recipe/{id}/track', [ProfileController::class, 'trackView'])->name('recipes.trackView');
});

// Включение маршрутов аутентификации
require __DIR__ . '/auth.php';

Route::get('/admin/actions/export', [AdminController::class, 'exportUserActions'])
    ->name('admin.actions.export')
    ->middleware(['auth', AdminMiddleware::class]);

Route::get('/admin/comments/export', [AdminController::class, 'exportComments'])
    ->name('admin.comments.export')
    ->middleware(['auth', AdminMiddleware::class]);

Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    // Главная страница админки
    Route::get('/', [AdminController::class, 'index'])
        ->name('index');

    // Категории
    Route::get('/categories', [CategoryController::class, 'index'])
        ->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])
        ->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])
        ->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
        ->name('categories.destroy');

    // Пользователи
    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');
    Route::post('/users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.updateRole');
    Route::post('/users/{user}/ban', [UserController::class, 'ban'])->name('users.ban');

    // Рецепты (админские функции)
    Route::get('/recipes', [AdminController::class, 'manageRecipes'])->name('recipes.index');
    Route::post('/recipes/{recipe}/reject', [AdminController::class, 'rejectRecipe'])->name('recipes.reject');
    Route::post('/recipes/{recipe}/approve', [AdminController::class, 'approveRecipe'])->name('recipes.approve');

    // Комментарии (админская функция)
    Route::get('/comments', [CommentController::class, 'index'])
        ->name('comments.index');

    // Ингредиенты (админская функция)
    Route::get('/ingredients', [IngredientController::class, 'index'])
        ->name('ingredients.index');
    Route::post('/ingredients/{ingredient}/approve', [IngredientController::class, 'approve'])
        ->name('ingredients.approve');
    Route::delete('/ingredients/{ingredient}', [IngredientController::class, 'destroy'])
        ->name('ingredients.destroy');

    // Маршруты для отчетов
    Route::get('/reports/comments/export', [AdminController::class, 'exportComments'])
        ->name('export.comments');
    Route::get('/reports/user-actions/export', [AdminController::class, 'exportUserActions'])
        ->name('export.user-actions');
    Route::get('/reports/recipes/export', [AdminController::class, 'exportRecipes'])
        ->name('export.recipes');
    Route::get('/reports/recipe-views/export', [AdminController::class, 'exportRecipeViews'])
        ->name('export.recipe-views');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/rejected-recipes', [NotificationController::class, 'index'])->name('rejected.recipes.index');
});



const express = require('express')
const favoritesController = require('../controller/favorites.controller')

const favoritesRouter = express.Router()

favoritesRouter.get('/menus', favoritesController.getMenus)
favoritesRouter.post('/menus', favoritesController.createFavoriteMenu)
favoritesRouter.delete('/menus', favoritesController.deleteFavoriteMenu)
favoritesRouter.get('/restaurants', favoritesController.getRestaurants)
favoritesRouter.post('/restaurants', favoritesController.createFavoriteResto)
favoritesRouter.delete('/restaurants', favoritesController.deleteFavoriteResto)


favoritesRouter.get('/promotions', favoritesController.getPromotions)


module.exports = favoritesRouter
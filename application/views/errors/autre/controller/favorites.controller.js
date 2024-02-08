const favoritesModel = require('../model/favorites.model')
const restoModel = require('../model/resto.model')
const menuModel = require('../model/menu.model')

const getMenus = async (req, res) => {
          try {
                    const allMenus = await favoritesModel.getMenus(req.clientId)
                    const menus = allMenus.map(menu => {
                              const { IMAGE_1, IMAGE_2, IMAGE_3, ...other } = menu
                              var IMAGES = []
                              if(IMAGE_1) IMAGES.push(IMAGE_1)
                              if(IMAGE_2) IMAGES.push(IMAGE_2)
                              if(IMAGE_3) IMAGES.push(IMAGE_3)
                              return {
                                        ...other,
                                        IMAGES
                              }
                    })
                    res.status(200).json(menus)
          } catch (error) {
                    console.log(error)
                    res.status(500).send('Server error')
          }
}

const createFavoriteMenu = async (req, res) => {
          const { menuRestoId } = req.body
          try {
                    const { insertId } = await favoritesModel.createFavoriteMenu(req.clientId, menuRestoId)
                    res.status(200).json({menuRestoId, ID_FAVORI: insertId})
          } catch (error) {
                    console.log(error)
          }
}

const deleteFavoriteMenu = async (req, res) => {
          const { menuRestoId } = req.body
          try {
                    await favoritesModel.deleteFavoriteMenu(req.clientId, menuRestoId)
                    res.status(200).json({menuRestoId, deleted: true})
          } catch (error) {
                    console.log(error)
          }
}

const createFavoriteResto = async (req, res) => {
          const { restoId } = req.body
          try {
                    const { insertId } = await favoritesModel.createFavoriteResto(req.clientId, restoId)
                    res.status(200).json({ restoId, ID_RESTO_FAVORI: insertId })
          } catch (error) {
                    console.log(error)
          }
}

const getRestaurants = async (req, res) => {
          try {
                    const restos = await favoritesModel.getRestaurants(req.clientId)
                    res.status(200).json(restos)
          } catch (error) {
                    console.log(error)
                    res.status(500).send('Server error')
          }
}

const deleteFavoriteResto = async (req, res) => {
          const { restoId } = req.body
          try {
                    await favoritesModel.deleteFavoriteResto(req.clientId, restoId)
                    res.status(200).json({ restoId, deleted: true })
          } catch (error) {
                    console.log(error)
          }
}

const getPromotions = async (req, res) => {
          try {
                    const promotions = await favoritesModel.getPromotions()
                    res.status(200).json(promotions)
          } catch (error) {
                    console.log(error)
          }
}


module.exports = {
          getMenus,
          getRestaurants,
          createFavoriteMenu,
          createFavoriteResto,
          deleteFavoriteMenu,
          deleteFavoriteResto,
          getPromotions
}
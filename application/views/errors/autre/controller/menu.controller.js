const menuModel = require('../model/menu.model')

const getAll = async (req, res) => {
          try {
                    const { categoryId, restoId, q, offset, limit } = req.query
                    const allMenus = await menuModel.getAll(categoryId, restoId, q, offset, limit)
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

module.exports = {
          getAll
}
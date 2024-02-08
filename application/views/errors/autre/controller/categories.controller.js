const categoriesModel = require('../model/categories.model.js')

const getAll = async (req, res) => {
          try {
                    const categories = await categoriesModel.getAll()
                    res.status(200).json(categories)
          } catch (error) {
                    console.log(error)
                    res.status(500).send('Server error')
          }
}

module.exports = {
          getAll
}
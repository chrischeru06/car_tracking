const provincesModel = require('../model/provinces.model')

const getAll = async (req, res) => {
          try {
                    const provinces = await provincesModel.getAll()
                    res.status(200).json(provinces)
          } catch (error) {
                    console.log(error)
                    res.status(500).send('Server error')
          }
}

module.exports = {
          getAll
}
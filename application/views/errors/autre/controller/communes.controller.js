const communesModel = require('../model/communes.model')

const getAll = async (req, res) => {
          try {
                    const { provinceId } = req.params
                    const communes = await communesModel.getAll(provinceId)
                    res.status(200).json(communes)
          } catch (error) {
                    console.log(error)
                    res.status(500).send('Server error')
          }
}

module.exports = {
          getAll
}
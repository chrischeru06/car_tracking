const zonesModel = require('../model/zones.model')

const getAll = async (req, res) => {
          try {
                    const { communeId } = req.params
                    const zones = await zonesModel.getAll(communeId)
                    res.status(200).json(zones)
          } catch (error) {
                    console.log(error)
                    res.status(500).send('Server error')
          }
}

module.exports = {
          getAll
}
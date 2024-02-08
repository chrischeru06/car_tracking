const express = require('express')
const restoController = require('../controller/resto.controller')

const restoRouter = express.Router()

restoRouter.get('/', restoController.getAll)

module.exports = restoRouter
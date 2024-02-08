const express = require('express')
const menuController = require('../controller/menu.controller')

const menuRouter = express.Router()

menuRouter.get('/', menuController.getAll)

module.exports = menuRouter
import express from "express"
const app = express();
app.get("/", (req,res) => {
    res.json({message: "Teste de Servidor na imagem Docker!!!"});
});
app.listen(3000, () => {
    console.log("Servidor utilizando a porta 3000");
});

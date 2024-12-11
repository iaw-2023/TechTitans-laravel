import express from "express";
import cors from "cors";

import { MercadoPagoConfig, Preference } from "./MercadoPago";

const client = new MercadoPagoConfig({
    accessToken: "APP_USR-1993633789627277-121109-39f8611c8d6d9c3f7d61daa11b784f9a-2151995286"
});

const app= express();
const port = 3000;

app.use(cors());
app.use(express.json());

app.get("/", (req, res) => {
    res.send("soy el server mp");
});

app.post("/create_preference", async (req, res) => {
    try{
        const body = {
            items: [
                {
                    title: req.body.title,
                    quantity: Number(req.body.quantity),
                    unit_price: Number(req.body.price),
                    currency_id: "ARS",
                },
            ],
         back_urls: {
            success: "http://localhost:3000/success",
            failure: "http://localhost:3000/failure",
            pending: "http://localhost:3000/pending",   
        },
        auto_return: "approved",
        };
        const preference = new Preference(client);
        const result = await preference.create({body});
        res.json({
            id: result.id,
        });
    }
    catch (error) {
        console.log(error);
        res.status(500).json({
            error: "error al crear la preferencia :( ->" + error.message,
        });
    }
});

app.listen(port, () => {
    console.log(`el servidor esta corriendo en puerrrrto ${port}`);
});
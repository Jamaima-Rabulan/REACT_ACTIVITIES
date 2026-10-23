
import React, { useState } from "react";
import "./App.css";
import Card from "./components/ui/Card";
import Counter from "./components/ui/Counter";
import List from "./components/ui/List";

function App() {
    const [name, setName] = useState("");
    const [submittedName, setSubmittedName] = useState("");

    const handleSubmit = (e) => {
        e.preventDefault();

        if (name.trim() !== "") {
            setSubmittedName(name.trim());
            setName("");
        }
    };

    return (
        <div className="App">
            <h1>Hello React!</h1>

            <Card
                name="Jamai"
                description="This is me"
            />

            <Card
                name="jamai"
                description="This is not me"
            />

            <form onSubmit={handleSubmit}>
                <input
                    type="text"
                    placeholder="Input your name"
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                />
                <button type="submit">Submit</button>
            </form>

            {submittedName && (
                <p>Hello, {submittedName}!</p>
            )}

            <Counter />

            <List />
        </div>
    );
}

export default App;
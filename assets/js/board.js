import React, { useEffect, useState } from "https://cdn.skypack.dev/react@17.0.1";
import ReactDOM from "https://cdn.skypack.dev/react-dom@17.0.1";


const App = () => {
  const formatValue = (value) => {
    if (value >= 1000000000) {
      return (value / 1000000000).toFixed(1).replace(/\.0$/, '') + "B";
    } else if (value >= 1000000) {
      return (value / 1000000).toFixed(1).replace(/\.0$/, '') + "M";
    } else if (value >= 1000) {
      return (value / 1000).toFixed(1).replace(/\.0$/, '') + "K";
    } else {
      return value;
    }
  };

  const [dados, setDados] = useState([]);

  useEffect(() => {
    fetch("data.json")
      .then(response => response.json())
      .then(data => setDados(data))
      .catch(error => console.error("Error fetching data:", error));
  }, []);

  const calculateAverage = (leader) => {
    const total = {
      level: parseFloat(leader.level),
      xp: parseFloat(leader.xp),
      coins: parseFloat(leader.coins),
    };

    const count = parseFloat(leader.beacons) || 1; 

    return {
      level: total.level / count,
      xp: total.xp / count,
      coins: total.coins / count,
    };
  };

  dados.sort((a, b) => {
    const avgA = calculateAverage(a);
    const avgB = calculateAverage(b);

    const avgValueA = (avgA.level + avgA.xp + avgA.coins) / 3;
    const avgValueB = (avgB.level + avgB.xp + avgB.coins) / 3;

    return avgValueB - avgValueA;
  });

  dados.forEach((leader) => {
    leader.level = formatValue(parseFloat(leader.level));
    leader.xp = formatValue(parseFloat(leader.xp));
    leader.coins = formatValue(parseFloat(leader.coins));
  });

  const setDefaultImage = (imageUrl) => {
    if (!imageUrl || !imageUrl.startsWith("https://")) {
      return "https://i.imgur.com/hvGDIDW.jpg";
    }
    return imageUrl;
  };

  dados.forEach((leader) => {
    leader.image = setDefaultImage(leader.image);
  });

  return (
    React.createElement("div", { className: "container" }, 
    React.createElement("div", { className: "topLeadersList" },
    dados.map((leader, index) => 
    React.createElement("div", { className: "leader", key: leader.id },
    index + 1 <= 3 && 
    React.createElement("div", { className: "containerImage" }, 
    React.createElement("img", { className: "image", loading: "lazy", src: leader.image }), 
    React.createElement("div", { className: "crown" }, 
    React.createElement("svg", {
      id: "crown1",
      fill: "#0f74b5",
      "data-name": "Layer 1",
      xmlns: "http://www.w3.org/2000/svg",
      viewBox: "0 0 100 50" }, 

    React.createElement("polygon", {
      className: "cls-1",
      points: "12.7 50 87.5 50 100 0 75 25 50 0 25.6 25 0 0 12.7 50" }))), 

    React.createElement("div", { className: "leaderName" }, leader.name))))), 

    React.createElement("div", { className: "playerslist" }, 
    React.createElement("div", { className: "table" }, 
    React.createElement("div", null, "#"), 

    React.createElement("div", null, "Name"), 

    React.createElement("div", null, "Total Requests"), 

    React.createElement("div", null, "Peak Requests"), 

    React.createElement("div", null, "Average Requests"), 

    React.createElement("div", null, " Duration"), 

    React.createElement("div", null, "Concurrent(s)"), 

                        React.createElement("div", null, "Date")), 

    React.createElement("div", { className: "list" },
    dados.map((leader, index) => 
    React.createElement("div", { className: "player", key: leader.id }, 
    React.createElement("span", null, " ", index + 1), 
    React.createElement("div", { className: "user" }, 
    React.createElement("img", { className: "image", src: leader.image }), 
    React.createElement("span", null, " ", leader.name, " ")), 

    React.createElement("span", null, " ", leader.level, " "), 
    React.createElement("span", null, " ", leader.xp, " "), 
    React.createElement("span", null, " ", leader.coins, " "), 
    React.createElement("span", null, " ", leader.love, " "), 
    React.createElement("span", null, " ", leader.beacons, " "), 
    React.createElement("span", null, " ", leader.resources, " ")))))));

};

ReactDOM.render( React.createElement(App, null),
document.getElementById("root"));


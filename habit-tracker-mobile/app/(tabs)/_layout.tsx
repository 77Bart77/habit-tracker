import { Ionicons } from "@expo/vector-icons";
import { Tabs } from "expo-router";
import React from "react";
import { useAuth } from "../../src/auth/AuthContext";

function TabIcon({
  name,
  color,
  size,
}: {
  name: keyof typeof Ionicons.glyphMap;
  color: string;
  size: number;
}) {
  return <Ionicons name={name} size={size} color={color} />;
}

export default function TabsLayout() {
  const { user } = useAuth(); // żeby np. ukryć profil jeśli nie zalogowany (opcjonalnie)

  return (
    <Tabs
  screenOptions={{
    headerShown: false,
    tabBarShowLabel: false,
    tabBarActiveTintColor: "white",
    tabBarInactiveTintColor: "rgba(255,255,255,0.55)",

    tabBarStyle: {
      position: "absolute",
      left: 19,
      right: 19,
      bottom: 20, // 🔥 uniesiony
      borderRadius: 24,
      height: 64,
      backgroundColor: "rgba(30,41,59,0.75)",
      borderTopWidth: 0,

      // Cień (iOS)
      shadowColor: "#000",
      shadowOpacity: 0.25,
      shadowRadius: 20,
      shadowOffset: { width: 0, height: 10 },

      // Android
      elevation: 10,
    },

    tabBarItemStyle: {
      paddingVertical: 6,
    },

    tabBarIcon: ({ color }) => (
  <TabIcon name="home-outline" color={color} size={26} />
),
  }}
>
      <Tabs.Screen
        name="home"
        options={{
          title: "Tablica",
          tabBarIcon: ({ color, size }) => (
            <TabIcon name="home-outline" color={color} size={size} />
          ),
        }}
      />

      <Tabs.Screen
        name="goals"
        options={{
          title: "Cele",
          tabBarIcon: ({ color, size }) => (
            <TabIcon name="checkbox-outline" color={color} size={size} />
          ),
        }}
      />

      <Tabs.Screen
  name="goals/[id]"
  options={{
    href: null, // ✅ ukrywa z tab bara i uniemożliwia wejście przez tab
  }}
/>

      <Tabs.Screen
        name="profile"
        options={{
          title: "Profil",
          tabBarIcon: ({ color, size }) => (
            <TabIcon name="person-outline" color={color} size={size} />
          ),
        }}
      />
    </Tabs>
  );
}
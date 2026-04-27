import React from "react";
import { ImageBackground, StyleSheet, View } from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";

const bg = require("../../assets/images/back.png");

export function AppBackground({ children }: { children: React.ReactNode }) {
  return (
    <ImageBackground source={bg} style={styles.bg} resizeMode="cover">
      {/* overlay, żeby było czytelniej */}
      <View style={styles.overlay} />

      <SafeAreaView style={styles.safe}>
        <View style={styles.content}>{children}</View>
      </SafeAreaView>
    </ImageBackground>
  );
}

const styles = StyleSheet.create({
  bg: { flex: 1 },
  overlay: {
    ...StyleSheet.absoluteFillObject,
    backgroundColor: "rgba(0,0,0,0.45)",
  },
  safe: { flex: 1 },
  content: { flex: 1, padding: 16 },
});
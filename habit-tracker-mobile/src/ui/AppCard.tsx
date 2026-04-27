import { BlurView } from "expo-blur";
import React from "react";
import { StyleSheet, View, ViewStyle } from "react-native";

type AppCardProps = {
  children: React.ReactNode;
  style?: ViewStyle;
};

export function AppCard({ children, style }: AppCardProps) {
  return (
    <View style={[styles.card, style]}>
      <BlurView intensity={40} tint="dark" style={styles.blur}>
        <View style={styles.inner}>{children}</View>
      </BlurView>
    </View>
  );
}

const styles = StyleSheet.create({
  card: {
    borderRadius: 22,
    overflow: "hidden",
    borderWidth: 1,
    borderColor: "rgba(255,255,255,0.18)",
    backgroundColor: "rgba(255,255,255,0.08)",
  },
  // KLUCZ: blur NIE może być absoluteFill, bo karta się „zeruje”
  blur: {
    borderRadius: 22,
  },
  inner: {
    padding: 18,
    backgroundColor: "rgba(0,0,0,0.25)",
  },
});
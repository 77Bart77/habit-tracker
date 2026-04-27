import React from "react";
import { Pressable, StyleSheet, Text, ViewStyle } from "react-native";
import { theme } from "./theme";

type Props = {
  title: string;
  onPress: () => void;
  style?: ViewStyle;
  size?: "md" | "sm";
  disabled?: boolean;
};

export function AppButton({
  title,
  onPress,
  style,
  size = "md",
}: Props) {
  return (
    <Pressable style={[styles.base, styles[size], style]} onPress={onPress}>
      <Text style={styles.text}>{title}</Text>
    </Pressable>
  );
}

const styles = StyleSheet.create({
  base: {
    borderRadius: theme.radius.md,
    alignItems: "center",
    justifyContent: "center",
    backgroundColor: theme.colors.glassStrong,
    borderWidth: 1,
    borderColor: "rgba(255,255,255,0.22)",
  },
  md: {
    paddingVertical: 12,
    paddingHorizontal: 16,
  },
  sm: {
    paddingVertical: 6,
    paddingHorizontal: 12,
  },
  text: {
    fontSize: 14,
    fontWeight: "700",
    color: theme.colors.text,
  },
});